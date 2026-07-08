<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../core/Config.php';
require_once __DIR__ . '/../Models/RegistrantModel.php';

class RegistrantController {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../../../core/Database.php';
        
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function handleRegistration() {
        date_default_timezone_set('Asia/Manila');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['status' => 'error', 'message' => 'Invalid request method.'];
        }

        //fetches data

        $registrantDetails = (object) [
            'event_id'       => isset($_POST['event_id']) ? intval($_POST['event_id']) : 0,
            'firstName'      => trim(filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT)),
            'middleName'     => trim(filter_input(INPUT_POST, 'middle_name', FILTER_DEFAULT)),
            'lastName'       => trim(filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT)),
            'email'          => trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)),
            'phone'          => trim(filter_input(INPUT_POST, 'contact_number', FILTER_DEFAULT)),
            'birthdateInput' => isset($_POST['birthdate']) ? trim($_POST['birthdate']) : ''

        ];

        $db = new Database();
        $dbConnection = $db->getConnection();
        $reg = new RegistrantModel($dbConnection);

        $errors = [];

        // 1. Validate Name Formatting
        $nameErrors = $reg->nameValidation($registrantDetails);
        if (is_array($nameErrors)) { $errors = array_merge($errors, $nameErrors); }

        // 2. Validate Basic Contact Info
        $contactErrors = $reg->contactInfoValidation($registrantDetails);
        if (is_array($contactErrors)) { $errors = array_merge($errors, $contactErrors); }

        // 3. Validate Email Domains (Run only if email structure was valid)
        if (empty($contactErrors)) {
            $domainErrors = $reg->emailDomainValidation($registrantDetails);
            if (is_array($domainErrors)) { $errors = array_merge($errors, $domainErrors); }
        }

        // 4. Validate Event Age Requirements
        $ageErrors = $reg->ageRequirementValidation($registrantDetails);
        if (is_array($ageErrors)) { $errors = array_merge($errors, $ageErrors); }


        // --- Evaluation ---
        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        // --- 1. Check Security & Rate Limits First (Before Saving Data) ---
        $lockCheck = $reg->checkOtpLockout($registrantDetails->email);
        if ($lockCheck['status'] === 'error') {
            return $lockCheck; // Stop execution and return the rate limit message
        }
        $latestOtpLog = $lockCheck['latest_log'];


        // --- 2. Save Registrant Record Using Data Object Properties ---
        $referenceId = $reg->save([
            'event_id'       => $registrantDetails->event_id,
            'first_name'     => $registrantDetails->firstName,
            'middle_name'    => $registrantDetails->middleName,
            'last_name'      => $registrantDetails->lastName,
            'birthdate'      => !empty($registrantDetails->birthdateInput) ? $registrantDetails->birthdateInput : null,
            'email'          => $registrantDetails->email,
            'contact_number' => $registrantDetails->phone,
            'is_verified'    => 0
        ]);

        if (!$referenceId) {
            return ['status' => 'error', 'message' => 'Critical storage transaction breakdown inside the model layer.'];
        }


        // --- 3. Process Security Token and Email Dispatching ---
        $emailDispatched = $reg->sendVerificationOtp(
            $registrantDetails->email,
            $registrantDetails->firstName,
            $registrantDetails->lastName,
            $latestOtpLog
        );

        if (!$emailDispatched) {
            return ['status' => 'error', 'message' => 'Communication failure triggering mailer handling.'];
        }


        // --- 4. Establish Application State Sessions & Redirect ---
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $_SESSION['pending_verification_email'] = $registrantDetails->email;
        $_SESSION['pending_reference_id']       = $referenceId;
        $_SESSION['last_otp_request_time']      = time();
        $_SESSION['current_backoff_cooldown']   = 30;

        header("Location: /PHP_Project/Walany/modules/Registrants/Views/otp-verification.php");
        exit();
    }

    public function verifyOTP() {
        date_default_timezone_set('Asia/Manila');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . '/../../../libs/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../../../libs/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../../libs/PHPMailer/src/SMTP.php';

        // 1. Structural Check: Ensure session didn't drop during the request transfer
        if (!isset($_SESSION['pending_verification_email']) || !isset($_SESSION['pending_reference_id'])) {
            return ['status' => 'error', 'message' => 'Active registration session expired or not found. Please re-register.'];
        }

        $email = $_SESSION['pending_verification_email'];
        $referenceId = $_SESSION['pending_reference_id']; 
        $currentTime = date('Y-m-d H:i:s');

        // 2. Handle Resend Dispatch Logic
        if (isset($_POST['action']) && $_POST['action'] === 'resend') {
            $timeSinceLastRequest = time() - (isset($_SESSION['last_otp_request_time']) ? $_SESSION['last_otp_request_time'] : 0);
            $currentCooldown = isset($_SESSION['current_backoff_cooldown']) ? $_SESSION['current_backoff_cooldown'] : 30;

            if ($timeSinceLastRequest < $currentCooldown) {
                return ['status' => 'error', 'message' => 'Please wait ' . ($currentCooldown - $timeSinceLastRequest) . ' seconds.'];
            }

            $_SESSION['current_backoff_cooldown'] = ($currentCooldown == 30) ? 60 : 300;
            $newPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $newExpiration = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $logStmt = $this->db->prepare("SELECT resend_count_hourly FROM walania_otp_logs WHERE email = ? ORDER BY id DESC LIMIT 1");
            $logStmt->execute([$email]);
            $latestLog = $logStmt->fetch(PDO::FETCH_ASSOC);
            $hourlyResends = $latestLog ? intval($latestLog['resend_count_hourly']) + 1 : 1;

            $insertStmt = $this->db->prepare("INSERT INTO walania_otp_logs (email, otp_code, attempts, resend_count_hourly, expires_at) VALUES (?, ?, 0, ?, ?)");
            $insertStmt->execute([$email, $newPin, $hourlyResends, $newExpiration]);

            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->setFrom('yeahlow24@gmail.com', 'Walania Event Management');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your New Registration Code: ' . $newPin;
                $mail->Body    = "Your code is: <strong>{$newPin}</strong>";
                $mail->send();

                $_SESSION['last_otp_request_time'] = time();
                return ['status' => 'success', 'message' => 'A fresh code has been dispatched.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => 'Failed to dispatch code.'];
            }
        }

        // 3. Handle Code Verification Logic
        $submittedOtp = isset($_POST['otp']) ? (is_array($_POST['otp']) ? trim(implode('', $_POST['otp'])) : trim($_POST['otp'])) : '';

        if (empty($submittedOtp)) {
            return ['status' => 'error', 'message' => 'Verification token empty or missing.'];
        }

        $checkLockStmt = $this->db->prepare("SELECT id, otp_code, attempts, expires_at, locked_until FROM walania_otp_logs WHERE email = ? ORDER BY id DESC LIMIT 1");
        $checkLockStmt->execute([$email]);
        $activeOtpRecord = $checkLockStmt->fetch(PDO::FETCH_ASSOC);

        if ($activeOtpRecord) {
            if ($activeOtpRecord['locked_until'] && strtotime($activeOtpRecord['locked_until']) > strtotime($currentTime)) {
                $timeLeft = ceil((strtotime($activeOtpRecord['locked_until']) - strtotime($currentTime)) / 60);
                return ['status' => 'error', 'message' => "Account locked out. Try again in {$timeLeft} minutes."];
            }

            if (strtotime($currentTime) > strtotime($activeOtpRecord['expires_at'])) {
                return ['status' => 'error', 'message' => 'The verification code has expired.'];
            }

            if ($submittedOtp === $activeOtpRecord['otp_code']) {
                $updateUser = $this->db->prepare("UPDATE walania_registrant SET is_verified = 1 WHERE reference_id = ?");
                $updateUser->execute([$referenceId]);

                $userStmt = $this->db->prepare("SELECT first_name, last_name FROM walania_registrant WHERE reference_id = ? LIMIT 1");
                $userStmt->execute([$referenceId]);
                $user = $userStmt->fetch(PDO::FETCH_ASSOC);

                // Safe path validation checking for phpqrcode library inclusion
                $qrLibPath = __DIR__ . '/../../../libs/phpqrcode/qrlib.php';
                if (file_exists($qrLibPath)) {
                    try {
                        require_once $qrLibPath;
                        $qrDir = __DIR__ . '/../../../uploads/qrcodes/';
                        if (!file_exists($qrDir)) { mkdir($qrDir, 0777, true); }
                        $qrFilePath = $qrDir . $referenceId . '.png';
                        QRcode::png($referenceId, $qrFilePath, QR_ECLEVEL_H, 6);

                        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host       = SMTP_HOST;
                        $mail->SMTPAuth   = true;
                        $mail->Username   = SMTP_USER;
                        $mail->Password   = SMTP_PASS;
                        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
                        $mail->setFrom('yeahlow24@gmail.com', 'Walania Event Management');
                        $mail->addAddress($email);
                        $mail->addEmbeddedImage($qrFilePath, 'qr_code_embed');
                        $mail->isHTML(true);
                        $mail->Subject = 'Your Event Entry Ticket Pass: ' . $referenceId;
                        $mail->Body    = "
                            <div style='font-family: Arial; padding: 20px;'>
                                <h2>Verification Successful! 🎉</h2>
                                <p>Hi <strong>{$user['first_name']}</strong>,</p>
                                <img src='cid:qr_code_embed' alt='Your QR Ticket'><br>
                                <strong>Reference ID: {$referenceId}</strong>
                            </div>";
                        $mail->send();
                        if (file_exists($qrFilePath)) { unlink($qrFilePath); }
                    } catch (Exception $e) {
                        error_log("Ticket Dispatch Fault: " . $e->getMessage());
                    }
                }

                $_SESSION['pending_reference_number'] = $referenceId;
                $_SESSION['success_timestamp'] = date('F d, Y h:i A');
                return ['status' => 'success', 'redirect' => 'registration-success'];
            } else {
                $newAttemptsCount = intval($activeOtpRecord['attempts']) + 1;
                
                if ($newAttemptsCount >= 3) {
                    $lockoutUntilTime = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                    $updateLog = $this->db->prepare("UPDATE walania_otp_logs SET attempts = ?, locked_until = ? WHERE id = ?");
                    $updateLog->execute([$newAttemptsCount, $lockoutUntilTime, $activeOtpRecord['id']]);
                    return ['status' => 'error', 'message' => 'Too many failed entries. Try again in 15 minutes.'];
                } else {
                    $updateLog = $this->db->prepare("UPDATE walania_otp_logs SET attempts = ? WHERE id = ?");
                    $updateLog->execute([$newAttemptsCount, $activeOtpRecord['id']]);
                    $triesRemaining = 3 - $newAttemptsCount;
                    return ['status' => 'error', 'message' => "Incorrect code. {$triesRemaining} attempts remaining."];
                }
            }
        }
        return ['status' => 'error', 'message' => 'Transaction context corrupted. Please re-register.'];
    }
}

// --- SECURE EXECUTION HOOK AT THE VERY BOTTOM OF THE FILE ---
    if (basename($_SERVER['SCRIPT_FILENAME']) === 'RegistrantController.php') {
        header('Content-Type: application/json');
        try {
            $instance = new RegistrantController();
            echo json_encode($instance->verifyOTP());
        } catch (Throwable $e) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Execution Environment Crash: ' . $e->getMessage()
            ]);
        }
        exit();
    }
?>