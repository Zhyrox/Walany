<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../core/Config.php';
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../Models/RegistrantModel.php';
require_once __DIR__ . '/../../../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../../libs/PHPMailer/src/SMTP.php';

class RegistrantController {
    private $model;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $this->model = new RegistrantModel($db);
    }

    public function loadEventContext(int $eventId): array {
        $eventData = ($eventId > 0) ? $this->model->getEventData($eventId) : null;
        $thumbnailValue = isset($eventData['thumbnail']) ? trim($eventData['thumbnail']) : '';
        
        return [
            'eventData' => $eventData,
            'eventName' => $eventData['name'] ?? 'Campus Event',
            'registrationImage' => (!empty($thumbnailValue) && $thumbnailValue !== 'uploads/events/default-banner.png') ? $thumbnailValue : '/Walany/assets/images/Event_Image%20(1).jpg'
        ];
    }
    
    public function handleRegistration() {
        date_default_timezone_set('Asia/Manila');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return ['status' => 'error', 'message' => 'Invalid request method.'];

        $registrantDetails = (object) [
            'event_id'       => isset($_POST['event_id']) ? intval($_POST['event_id']) : 0,
            'firstName'      => trim(filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT)),
            'middleName'     => trim(filter_input(INPUT_POST, 'middle_name', FILTER_DEFAULT)),
            'lastName'       => trim(filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT)),
            'email'          => trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)),
            'phone'          => trim(filter_input(INPUT_POST, 'contact_number', FILTER_DEFAULT)),
            'birthdateInput' => isset($_POST['birthdate']) ? trim($_POST['birthdate']) : ''
        ];

        if (!$this->model->validateRegistration($registrantDetails)) {
            return ['status' => 'error', 'errors' => $this->model->getErrors()];
        }

        $lockCheck = $this->model->checkOtpLockoutState($registrantDetails->email);
        if ($lockCheck['status'] === 'error') return $lockCheck;

        $referenceId = $this->model->save([
            'event_id' => $registrantDetails->event_id, 'first_name' => $registrantDetails->firstName, 'middle_name' => $registrantDetails->middleName, 'last_name' => $registrantDetails->lastName, 'birthdate' => !empty($registrantDetails->birthdateInput) ? $registrantDetails->birthdateInput : null, 'email' => $registrantDetails->email, 'contact_number' => $registrantDetails->phone, 'is_verified' => 0
        ]);
        if (!$referenceId) return ['status' => 'error', 'message' => 'Critical storage transaction breakdown inside the model layer.'];

        if (!$this->sendVerificationOtpWorkflow($registrantDetails->email, $registrantDetails->firstName, $registrantDetails->lastName, $lockCheck['latest_log'])) {
            return ['status' => 'error', 'message' => 'Communication failure triggering mailer handling.'];
        }

        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION['pending_verification_email'] = $registrantDetails->email;
        $_SESSION['pending_reference_id']       = $referenceId;
        $_SESSION['last_otp_request_time']      = time();
        $_SESSION['current_backoff_cooldown']   = 30;

        header("Location: /Walany/modules/Registrants/Views/otp-verification.php");
        exit();
    }

    public function verifyOTP() {
        date_default_timezone_set('Asia/Manila');
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        if (!isset($_SESSION['pending_verification_email']) || !isset($_SESSION['pending_reference_id'])) {
            return ['status' => 'error', 'message' => 'Active registration session expired or not found. Please re-register.'];
        }

        $email = $_SESSION['pending_verification_email'];
        $referenceId = $_SESSION['pending_reference_id'];

        if (isset($_POST['action']) && $_POST['action'] === 'resend') {
            $timeSinceLastRequest = time() - ($_SESSION['last_otp_request_time'] ?? 0);
            $resendResult = $this->model->processOtpResendBackoff($email, $timeSinceLastRequest, $_SESSION['current_backoff_cooldown'] ?? 30);
            
            if ($resendResult['status'] === 'error') return $resendResult;
            
            $_SESSION['current_backoff_cooldown'] = $resendResult['new_cooldown'];
            $this->dispatchMail($email, 'Your New Registration Code: ' . $resendResult['new_pin'], "Your code is: <strong>{$resendResult['new_pin']}</strong>");
            $_SESSION['last_otp_request_time'] = time();
            return ['status' => 'success', 'message' => 'A fresh code has been dispatched.'];
        }

        $submittedOtp = isset($_POST['otp']) ? (is_array($_POST['otp']) ? trim(implode('', $_POST['otp'])) : trim($_POST['otp'])) : '';
        if (empty($submittedOtp)) return ['status' => 'error', 'message' => 'Verification token empty or missing.'];

        $activeOtpRecord = $this->model->getLatestOtpLog($email);
        if (!$activeOtpRecord) return ['status' => 'error', 'message' => 'Transaction context corrupted. Please re-register.'];

        $currentTime = date('Y-m-d H:i:s');
        if ($activeOtpRecord['locked_until'] && strtotime($activeOtpRecord['locked_until']) > strtotime($currentTime)) {
            $timeLeft = ceil((strtotime($activeOtpRecord['locked_until']) - strtotime($currentTime)) / 60);
            return ['status' => 'error', 'message' => "Account locked out. Try again in {$timeLeft} minutes."];
        }
        if (strtotime($currentTime) > strtotime($activeOtpRecord['expires_at'])) return ['status' => 'error', 'message' => 'The verification code has expired.'];

        if ($submittedOtp === $activeOtpRecord['otp_code']) {
            $this->model->verifyRegistrant($referenceId);
            $this->dispatchSuccessTicketWithQr($email, $referenceId, $this->model->getRegistrantByRef($referenceId));

            // 1. Ensure you have the event ID (pull from request parameters or current session context)
            $eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : (isset($_SESSION['pending_event_id']) ? (int)$_SESSION['pending_event_id'] : 1);

            // 2. Fetch the clean data payload through your existing controller architecture
            $context = $this->loadEventContext($eventId);
            $eventDetails = $context['eventData'] ?? [];

            // 3. Populate the success session array safely
            $_SESSION['pending_reference_number'] = $referenceId;
            $_SESSION['success_timestamp'] = date('F d, Y h:i A');

            $_SESSION['registered_event_data'] = [
                'name'        => $eventDetails['name'],
                'event_date'  => $eventDetails['event_date'],
                'description' => $eventDetails['description'],
                'location'    => $eventDetails['location'] ?? 'Walania Designated Venue'
            ];

            return ['status' => 'success', 'redirect' => 'process-payment'];
        }

        return $this->model->incrementOtpAttempts($activeOtpRecord);
    }

    public function redirectToPayMongoCheckout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Fetch your clean reference ID context from the session
        $evalReferenceId = $_SESSION['pending_reference_id'] ?? null;
        
        if (!$evalReferenceId) {
            header("Location: /Walany/index.php?module=Home&error=session_loss");
            exit();
        }

        // 2. Retrieve registrant record to identify which event they registered for
        $registrant = $this->model->getRegistrantByRef($evalReferenceId);
        $eventId = $registrant['event_id'] ?? ($_SESSION['pending_event_id'] ?? 1);

        // 3. Fetch event details (including the dynamic 'price' column) from walania_event
        $eventData = $this->model->getEventData((int)$eventId);
        
        // Default to 250.00 if price is missing/0, then convert to centavos for PayMongo
        $rawPrice = (!empty($eventData['price']) && $eventData['price'] > 0) ? (float)$eventData['price'] : 250.00;
        $amountInCents = (int)round($rawPrice * 100);
        $eventName = $eventData['name'] ?? 'Event Registration Fee';

        // 4. Generate an independent, unique merchant track token for PayMongo
        $paymentTrackingRef = "PAY-" . $evalReferenceId . "-" . time();

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . "/Walany";

        // 5. Prepare the strict JSON payload with dynamic event name & amount
        $payload = json_encode([
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description'   => true,
                    'show_line_items'    => true,
                    'line_items' => [
                        [
                            'amount'      => $amountInCents,
                            'currency'    => 'PHP',
                            'name'        => $eventName,
                            'quantity'    => 1
                        ]
                    ],
                    'payment_method_types' => ['gcash', 'paymaya', 'card'],
                    'reference_number'     => $paymentTrackingRef,
                    'description'          => 'Registration fee for ' . $eventName,
                    'success_url'          => $baseUrl . "/modules/Registrants/Views/payment-callback.php?status=success&ref=" . urlencode($evalReferenceId),
                    'cancel_url'           => $baseUrl . "/modules/Registrants/Views/payment-callback.php?status=cancelled"
                ]
            ]
        ]);

        // 6. Send payload to PayMongo Checkout Sessions API endpoint via cURL
        $ch = curl_init('https://api.paymongo.com/v1/checkout_sessions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode(PAYMONGO_SECRET_KEY . ':')
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $result = json_decode($response, true);
            $checkoutUrl = $result['data']['attributes']['checkout_url'] ?? null;

            if ($checkoutUrl) {
                header("Location: " . $checkoutUrl);
                exit();
            }
        }

        // Debugging backup output in case API handshake fails
        die("PayMongo API Handshake Failed. HTTP Server Response Code: " . $httpCode);
    }

    private function sendVerificationOtpWorkflow(string $email, string $firstName, string $lastName, ?array $latestOtpLog): bool {
        $sixDigitPin = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Check if the previous OTP request was made within the last 60 minutes
        $logCreatedAt = $latestOtpLog ? strtotime($latestOtpLog['created_at'] ?? 'now') : 0;
        $isWithinHour = $logCreatedAt > strtotime('-1 hour');

        // If inside the 1-hour window, increment count; otherwise reset to 1
        $this->model->createOtpLog($email, $sixDigitPin, $latestOtpLog ? (int)$latestOtpLog['resend_count_hourly'] + 1 : 1, date('Y-m-d H:i:s', strtotime('+5 minutes')));
        
        $body = "<div style='font-family: Arial; padding: 25px; max-width: 480px; margin: auto;'>
                    <h2>Email Verification Code</h2>
                    <p>Hello <strong>{$firstName}</strong>,</p>
                    <div style='text-align: center; margin: 30px 0;'><span style='font-family: monospace; font-size: 34px; font-weight: bold; padding: 12px 24px; background-color: #f1f3f5; border-radius: 8px; color: #0d6efd;'>{$sixDigitPin}</span></div>
                 </div>";
        return $this->dispatchMail($email, 'Verify Your Registration Identity Code: ' . $sixDigitPin, $body);
    }

    private function dispatchMail(string $to, string $subject, string $body): bool {
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP(); $mail->Host = SMTP_HOST; $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER; $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; $mail->Port = 587;
            $mail->setFrom('yeahlow24@gmail.com', 'Walania Event Management');
            $mail->addAddress($to); $mail->isHTML(true);
            $mail->Subject = $subject; $mail->Body = $body;
            return $mail->send();
        } catch (PDOException $e) {
            // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

            // 2. Safely redirect the user to the generic error container view without leaking structure schemas
            header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
            exit;
        }
    }

    private function dispatchSuccessTicketWithQr(string $email, string $referenceId, ?array $user) {
        $qrLibPath = __DIR__ . '/../../../libs/phpqrcode/qrlib.php';
        if (!file_exists($qrLibPath)) return;

        try {
            require_once $qrLibPath;
            $qrDir = __DIR__ . '/../../../uploads/qrcodes/';
            if (!file_exists($qrDir)) mkdir($qrDir, 0777, true);
            $qrFilePath = $qrDir . $referenceId . '.png';
            QRcode::png($referenceId, $qrFilePath, QR_ECLEVEL_H, 6);

            // Fetch event data
            $eventId = (int)($user['event_id'] ?? 1);
            $eventData = $this->model->getEventData($eventId);
            $eventName = htmlspecialchars($eventData['name'] ?? 'Walania Event');
            $firstName = htmlspecialchars($user['first_name'] ?? 'Registrant');

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP(); 
            $mail->Host = SMTP_HOST; 
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER; 
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587;
            
            // SENDER ALIAS & DISPLAY NAME
            $mail->setFrom('yeahlow24@gmail.com', 'Walania Events (No-Reply)');
            
            $mail->addAddress($email); 
            $mail->addEmbeddedImage($qrFilePath, 'qr_code_embed');
            $mail->isHTML(true); 

            // CUSTOM EMAIL SUBJECT
            $mail->Subject = "CONFIRMED: Your Registration Pass for {$eventName} [Ref: {$referenceId}]";

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 25px; color: #333; max-width: 520px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px;'>
                    <h2 style='color: #0d6efd; margin-top: 0;'>Registration Successful! 🎉</h2>
                    
                    <p>Hi <strong>{$firstName}</strong>,</p>
                    
                    <p>We are excited to confirm that your registration for <strong>{$eventName}</strong> has been processed successfully. Your spot is officially secured, and your unique entry pass details are attached below. Please present the generated QR code at the venue check-in desk or keep it accessible for digital verification upon arrival. For online webinars, official meeting links and access guidelines will be followed up directly by the event planner in a separate email prior to the schedule. We appreciate your participation and look forward to having you at the event!</p>
                    
                    <div style='text-align: center; margin: 25px 0;'>
                        <img src='cid:qr_code_embed' alt='Your QR Ticket' style='max-width: 180px; height: auto;'><br>
                        <span style='font-family: monospace; font-size: 16px; font-weight: bold; color: #555;'>Reference ID: {$referenceId}</span>
                    </div>

                    <div style='background-color: #f8f9fa; padding: 12px; border-left: 4px solid #0d6efd; margin-top: 20px; font-size: 13px; color: #6c757d;'>
                        <strong>Note:</strong> Please do not reply directly to this automated email. Keep this pass safe for check-in verification.
                    </div>
                </div>
            ";

            $mail->send();
            if (file_exists($qrFilePath)) unlink($qrFilePath);

        } catch (\Exception $e) {
            error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Email dispatch or QR generator error."));
            exit;
        }
    }
}

// --- SECURE EXECUTION INTERFACE HOOK ---
if (basename($_SERVER['SCRIPT_FILENAME']) === 'RegistrantController.php') {
    header('Content-Type: application/json');
    try {
        $instance = new RegistrantController();
        echo json_encode($instance->verifyOTP());
    } catch (PDOException $e) {
        // 1. Log the absolute descriptive raw traceback details to XAMPP error logs for the server administrator
        error_log("CRITICAL SYSTEM INTEGRITY FAULT: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());

        // 2. Safely redirect the user to the generic error container view without leaking structure schemas
        header("Location: /Walany/index.php?module=Admin&action=system_error&message=" . urlencode("Database connectivity or operational schema fault."));
        exit;
    }
    exit();
}