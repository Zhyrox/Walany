<?php
require_once __DIR__ . '/../../../core/Database.php';

class RegistrantModel{
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }

    /**
     * Generates a clean ABCD-1234 formatted reference token split evenly between letters and numbers
     */
    private function generateReferenceToken() {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        
        $part1 = '';
        $part2 = '';
        
        for ($i = 0; $i < 4; $i++) {
            $part1 .= $letters[rand(0, strlen($letters) - 1)];
        }
        
        for ($i = 0; $i < 4; $i++) {
            $part2 .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        
        return $part1 . '-' . $part2;
    }

    public function save($data) {
        try {
            // 1. Generate a clean token and loop-check to ensure it's uniquely clear
            $isUnique = false;
            $referenceId = '';
            
            while (!$isUnique) {
                $referenceId = $this->generateReferenceToken();
                
                $checkSql = "SELECT COUNT(*) FROM walania_registrant WHERE reference_id = :ref";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute([':ref' => $referenceId]);
                
                if ($checkStmt->fetchColumn() == 0) {
                    $isUnique = true;
                }
            }

            // 2. Insert execution mapped cleanly to include event_id, reference_id, birthdate, and is_verified
            $sql = "INSERT INTO walania_registrant (event_id, reference_id, first_name, middle_name, last_name, birthdate, email, contact_number, is_verified)
                    VALUES (:event_id, :reference_id, :first_name, :middle_name, :last_name, :birthdate, :email, :contact_number, :is_verified)";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':event_id'       => $data['event_id'],
                ':reference_id'   => $referenceId,
                ':first_name'     => $data['first_name'],
                ':middle_name'    => !empty($data['middle_name']) ? $data['middle_name'] : null,
                ':last_name'      => $data['last_name'],
                ':birthdate'      => !empty($data['birthdate']) ? $data['birthdate'] : null,
                ':email'          => $data['email'],
                ':contact_number' => $data['contact_number'],
                ':is_verified'    => $data['is_verified']
            ]);

            // Return the generated key string back to the controller layer
            return $referenceId;
            
        } catch (PDOException $e) {
            // Log the actual error silently to system logs for debugging
            error_log("Database Save Failure: " . $e->getMessage());
            return false;
        }
    }


    public function nameValidation($registrantDetails){
        $errors = [];
        $nameRegex = "/^[a-zA-ZñÑ\s\-]+$/";

        $firstName  = $registrantDetails->firstName;
        $middleName = $registrantDetails->middleName;
        $lastName = $registrantDetails-> lastName;

        if (empty($firstName)) { $errors[] = "First name is required."; }
        elseif (!preg_match($nameRegex, $firstName)) { $errors[] = "First name contains invalid characters."; }

        if (!empty($middleName) && !preg_match($nameRegex, $middleName)) { $errors[] = "Middle name contains invalid characters."; }

        if (empty($lastName)) { $errors[] = "Last name is required."; }
        elseif (!preg_match($nameRegex, $lastName)) { $errors[] = "Last name contains invalid characters."; }

        if($errors){
            return $errors;
        } else {
            return true;
        }
    }

    public function contactInfoValidation($registrantDetails){
        $phoneRegex = "/^09[0-9]{9}$/";
        $errors = [];

        $email = $registrantDetails->email;
        $phone = $registrantDetails->phone;

        if (empty($phone)) { $errors[] = "Phone number is required."; }
        elseif (!preg_match($phoneRegex, $phone)) { $errors[] = "Phone number must be a valid 11-digit string starting with 09."; }

        if (!$email) { $errors[] = "Please provide a valid email address."; }

        if($errors){
            return $errors;
        } else {
            return true;
        }
    }

    public function emailDomainValidation($registrantDetails) {
        $errors = [];
        $email = $registrantDetails->email;

        if (!$email) {
            return ["Please provide a valid email address."];
        }

        $disallowedDomains = ['example.com', 'test.com', 'domain.com', 'mailinator.com', 'yopmail.com'];
        $emailParts = explode('@', $email);
        $domain = strtolower(end($emailParts));

        if (in_array($domain, $disallowedDomains)) {
            $errors[] = "Registration blocked: Real-world email addresses only.";
        }

        // checkdnsrr can be slow, so only check it if no errors found yet
        if (empty($errors) && !checkdnsrr($domain, 'MX')) {
            $errors[] = "The email domain provided does not appear to host a valid mail server.";
        }

        return !empty($errors) ? $errors : true;
    }

    public function ageRequirementValidation($registrantDetails) {
        $errors = [];
        $email = $registrantDetails->email;
        $firstName = $registrantDetails->firstName;
        $lastName = $registrantDetails->lastName;
        $birthdateInput = $registrantDetails->birthdateInput;
        $eventId = $registrantDetails->event_id;

        $stmt = $this->db->prepare("SELECT birthdate FROM walania_registrant WHERE email = ? AND first_name = ? AND last_name = ? LIMIT 1");
        $stmt->execute([$email, $firstName, $lastName]);
        $historicalUser = $stmt->fetch(PDO::FETCH_ASSOC);

        $effectiveBirthdate = $historicalUser ? $historicalUser['birthdate'] : $birthdateInput;

        // Calculates age safely
        if (!empty($effectiveBirthdate)) {
            $birthDateObj = new DateTime($effectiveBirthdate);
            $currentDateObj = new DateTime();
            $calculatedAge = $currentDateObj->diff($birthDateObj)->y;
        } else {
            $calculatedAge = 0;
        }

        // Check event age requirements
        $eventStmt = $this->db->prepare("SELECT is_adult_only FROM walania_event WHERE id = ? LIMIT 1");
        $eventStmt->execute([$eventId]);
        $eventData = $eventStmt->fetch(PDO::FETCH_ASSOC);

        if ($eventData && isset($eventData['is_adult_only']) && $eventData['is_adult_only'] == 1 && $calculatedAge < 18) {
            $errors[] = "Registration Denied: This specific event requires you to be 18+.";
        }

        return !empty($errors) ? $errors : true;
    }

    public function checkOtpLockout($email) {
        $currentTime = date('Y-m-d H:i:s');
        $lockStmt = $this->db->prepare("SELECT locked_until, resend_count_hourly FROM walania_otp_logs WHERE email = ? ORDER BY id DESC LIMIT 1");
        $lockStmt->execute([$email]);
        $latestOtpLog = $lockStmt->fetch(PDO::FETCH_ASSOC);

        if ($latestOtpLog) {
            if ($latestOtpLog['locked_until'] && strtotime($latestOtpLog['locked_until']) > strtotime($currentTime)) {
                $timeLeft = ceil((strtotime($latestOtpLog['locked_until']) - strtotime($currentTime)) / 60);
                return ['status' => 'error', 'message' => "Too many verification failures. Locked for {$timeLeft} minutes."];
            }
            if (intval($latestOtpLog['resend_count_hourly']) >= 6) {
                return ['status' => 'error', 'message' => 'Hourly security request threshold reached. Please wait an hour.'];
            }
        }

        // Return the log information so we can reuse it later when generating the new OTP log
        return ['status' => 'success', 'latest_log' => $latestOtpLog];
    }

    /**
     * Generates an OTP, saves it to the database logs, and emails it to the user.
     */
    public function sendVerificationOtp($email, $firstName, $lastName, $latestOtpLog) {
        $sixDigitPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expirationTimestamp = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        $hourlyResends = $latestOtpLog ? intval($latestOtpLog['resend_count_hourly']) + 1 : 1;

        // Log OTP to database
        $logStmt = $this->db->prepare("INSERT INTO walania_otp_logs (email, otp_code, attempts, resend_count_hourly, expires_at) VALUES (?, ?, 0, ?, ?)");
        $logStmt->execute([$email, $sixDigitPin, $hourlyResends, $expirationTimestamp]);

        // Send Email using PHPMailer
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
            return true;

        } catch (Exception $e) {
            error_log("Mailer Exception caught: " . $e->getMessage());
            return false;
        }
    }
}