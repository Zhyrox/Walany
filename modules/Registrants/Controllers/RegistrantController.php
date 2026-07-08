<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../core/Config.php';


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

        $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

        $firstName  = trim(filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT));
        $middleName = trim(filter_input(INPUT_POST, 'middle_name', FILTER_DEFAULT));
        $lastName   = trim(filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT));
        $email      = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $phone      = trim(filter_input(INPUT_POST, 'contact_number', FILTER_DEFAULT));
        $birthdateInput = isset($_POST['birthdate']) ? trim($_POST['birthdate']) : '';

        $errors = [];
        $nameRegex = "/^[a-zA-ZñÑ\s\-]+$/";

        if (empty($firstName)) { $errors[] = "First name is required."; }
        elseif (!preg_match($nameRegex, $firstName)) { $errors[] = "First name contains invalid characters."; }

        if (!empty($middleName) && !preg_match($nameRegex, $middleName)) { $errors[] = "Middle name contains invalid characters."; }

        if (empty($lastName)) { $errors[] = "Last name is required."; }
        elseif (!preg_match($nameRegex, $lastName)) { $errors[] = "Last name contains invalid characters."; }

        if (!$email) { $errors[] = "Please provide a valid email address."; }

        $phoneRegex = "/^09[0-9]{9}$/";
        if (empty($phone)) { $errors[] = "Phone number is required."; }
        elseif (!preg_match($phoneRegex, $phone)) { $errors[] = "Phone number must be a valid 11-digit string starting with 09."; }

        if (!empty($errors)) { return ['status' => 'error', 'errors' => $errors]; }

        $disallowedDomains = ['example.com', 'test.com', 'domain.com', 'mailinator.com', 'yopmail.com'];
        $emailParts = explode('@', $email);
        $domain = strtolower(end($emailParts));

        if (in_array($domain, $disallowedDomains)) {
            return ['status' => 'error', 'message' => 'Registration blocked: Real-world email addresses only.'];
        }

        if (!checkdnsrr($domain, 'MX')) {
            return ['status' => 'error', 'message' => 'The email domain provided does not appear to host a valid mail server.'];
        }

        require_once __DIR__ . '/../Models/RegistrantModel.php';
        $model = new Registrant();

        $stmt = $this->db->prepare("SELECT birthdate FROM walania_registrant WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $historicalUser = $stmt->fetch(PDO::FETCH_ASSOC);

        $effectiveBirthdate = $historicalUser ? $historicalUser['birthdate'] : $birthdateInput;

        if (!empty($effectiveBirthdate)) {
            $birthDateObj = new DateTime($effectiveBirthdate);
            $currentDateObj = new DateTime();
            $calculatedAge = $currentDateObj->diff($birthDateObj)->y;
        } else {
            $calculatedAge = 0;
        }

        $targetEventId = $event_id;
        $eventStmt = $this->db->prepare("SELECT is_adult_only FROM walania_event WHERE id = ? LIMIT 1");
        $eventStmt->execute([$targetEventId]);
        $eventData = $eventStmt->fetch(PDO::FETCH_ASSOC);

        if ($eventData && isset($eventData['is_adult_only']) && $eventData['is_adult_only'] == 1 && $calculatedAge < 18) {
            return ['status' => 'error', 'message' => 'Registration Denied: This specific event requires you to be 18+.'];
        }

        $currentTime = date('Y-m-d H:i:s');
        $lockStmt = $this->db->prepare("SELECT locked_until, resend_count_hourly FROM walania_otp_logs WHERE email = ? ORDER BY id DESC LIMIT 1");
        $lockStmt->execute([$email]);
        $latestOtpLog = $lockStmt->fetch(PDO::FETCH_ASSOC);

        if ($latestOtpLog) {
            if ($latestOtpLog['locked_until'] && strtotime($latestOtpLog['locked_until']) > strtotime($currentTime)) {
                $timeLeft = ceil((strtotime($latestOtpLog['locked_until']) - strtotime($currentTime)) / 60);
                return ['status' => 'error', 'message' => "Too many verification failures. Locked for {$timeLeft} minutes."];
            }
            if (intval($latestOtpLog['resend_count_hourly']) >= 10) {
                return ['status' => 'error', 'message' => 'Hourly security request threshold reached. Please wait an hour.'];
            }
        }

        // --- SAVING DATA INTO THE REPAIRED MODEL ---
        $saveSuccess = $model->save([
            'event_id'       => $targetEventId,
            'first_name'     => $firstName,
            'middle_name'    => $middleName,
            'last_name'      => $lastName,
            'birthdate'      => $effectiveBirthdate, 
            'email'          => $email,
            'contact_number' => $phone,
            'is_verified'    => 0 
        ]);

        if ($saveSuccess) {
            $referenceId = $saveSuccess; // Securely capture generated ABCD-1234 token

            $sixDigitPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expirationTimestamp = date('Y-m-d H:i:s', strtotime('+5 minutes'));
            $hourlyResends = $latestOtpLog ? intval($latestOtpLog['resend_count_hourly']) + 1 : 1;

            $logStmt = $this->db->prepare("INSERT INTO walania_otp_logs (email, otp_code, attempts, resend_count_hourly, expires_at) VALUES (?, ?, 0, ?, ?)");
            $logStmt->execute([$email, $sixDigitPin, $hourlyResends, $expirationTimestamp]);

            try {
                require_once __DIR__ . '/../../../libs/PHPMailer/src/Exception.php';
                require_once __DIR__ . '/../../../libs/PHPMailer/src/PHPMailer.php';
                require_once __DIR__ . '/../../../libs/PHPMailer/src/SMTP.php';

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('yeahlow24@gmail.com', 'Walania Event Management');
                $mail->addAddress($email, $firstName . ' ' . $lastName);
                $mail->isHTML(true);
                $mail->Subject = 'Verify Your Registration Identity Code: ' . $sixDigitPin;
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; padding: 25px; max-width: 480px; margin: auto;'>
                        <h2>Email Verification Code</h2>
                        <p>Hello <strong>{$firstName}</strong>,</p>
                        <p>Your verification token is:</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <span style='font-family: monospace; font-size: 34px; font-weight: bold; padding: 12px 24px; background-color: #f1f3f5; border-radius: 8px; color: #0d6efd;'>{$sixDigitPin}</span>
                        </div>
                    </div>";

                $mail->send();

                if (session_status() === PHP_SESSION_NONE) { session_start(); }
                $_SESSION['pending_verification_email'] = $email;
                $_SESSION['pending_reference_id']       = $referenceId; // Match column structural name
                $_SESSION['last_otp_request_time']      = time();
                $_SESSION['current_backoff_cooldown']   = 30; 

                if (session_status() === PHP_SESSION_NONE) { session_start(); }
                $_SESSION['pending_verification_email'] = $email;
                $_SESSION['pending_reference_id']       = $referenceId;
                $_SESSION['last_otp_request_time']      = time();
                $_SESSION['current_backoff_cooldown']   = 30;

                header("Location: /PHP_Project/Walany/modules/Registrants/Views/otp-verification.php");
                exit();

            } catch (Exception $e) {
                return ['status' => 'error', 'message' => 'Communication failure triggering mailer handling.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Critical storage transaction breakdown inside the model layer.'];
        }
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