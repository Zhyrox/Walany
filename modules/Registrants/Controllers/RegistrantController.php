<?php
class RegistrantController {
    
    /**
     * Intercepts and validates the incoming POST registration data.
     * @return array Feedback status and error details.
     */
    public function handleRegistration() {
        // Ensure we are processing an actual POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'status' => 'error',
                'message' => 'Invalid request method.'
            ];
        }

        // 1. Sanitize raw strings to block basic script injections
        $event_id   = trim(filter_input(INPUT_POST, 'event_id', FILTER_DEFAULT));
        $firstName  = trim(filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT));
        $middleName = trim(filter_input(INPUT_POST, 'middle_name', FILTER_DEFAULT));
        $lastName   = trim(filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT));
        $email      = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $phone      = trim(filter_input(INPUT_POST, 'contact_number', FILTER_DEFAULT));

        $errors = [];

        // 2. Strict RegEx Validation for Names
        // Allows letters, spaces, hyphens, and local characters (like Ñ/ñ)
        $nameRegex = "/^[a-zA-ZñÑ\s\-]+$/";

        if (empty($firstName)) {
            $errors[] = "First name is required.";
        } elseif (!preg_match($nameRegex, $firstName)) {
            $errors[] = "First name contains invalid characters or numbers.";
        }

        // Middle name is optional, but if provided, it must be valid
        if (!empty($middleName) && !preg_match($nameRegex, $middleName)) {
            $errors[] = "Middle name contains invalid characters or numbers.";
        }

        if (empty($lastName)) {
            $errors[] = "Last name is required.";
        } elseif (!preg_match($nameRegex, $lastName)) {
            $errors[] = "Last name contains invalid characters or numbers.";
        }

        // 3. Email Validation Check
        if (!$email) {
            $errors[] = "Please provide a valid, well-formed email address.";
        }

        // 4. Strict Phone Number Validation (Philippine Mobile Format)
        // Matches typical 11-digit mobile structures starting with 09 (e.g., 09123456789)
        $phoneRegex = "/^09[0-9]{9}$/";
        if (empty($phone)) {
            $errors[] = "Phone number is required.";
        } elseif (!preg_match($phoneRegex, $phone)) {
            $errors[] = "Phone number must be a valid 11-digit numeric string starting with 09.";
        }

        // 5. Evaluation & Response Return
        if (!empty($errors)) {
            return [
                'status' => 'error',
                'errors' => $errors
            ];
        }

        require_once __DIR__ . '/../Models/RegistrantModel.php';
        $model = new Registrant();
        
        $saveSuccess = $model->save([
            'event_id'       => isset($_POST['event_id']) ? intval($_POST['event_id']) : 1,
            'first_name'     => isset($_POST['first_name']) ? trim($_POST['first_name']) : '',
            'middle_name'    => isset($_POST['middle_name']) ? trim($_POST['middle_name']) : '',
            'last_name'      => isset($_POST['last_name']) ? trim($_POST['last_name']) : '',
            'email'          => isset($_POST['email']) ? trim($_POST['email']) : '',
            'contact_number' => isset($_POST['contact_number']) ? trim($_POST['contact_number']) : (isset($_POST['contact_number']) ? trim($_POST['contact_number']) : '')
        ]);

        if ($saveSuccess) {
            $referenceNumber = $saveSuccess; // Your unique token string e.g., ABCD-1234

            // --- START: GENERATE QR CODE & EMAIL DELIVERY SYSTEM ---
            try {
                // 1. Include Free Local Libraries
                require_once __DIR__ . '/../../../libs/phpqrcode/qrlib.php';
                require_once __DIR__ . '/../../../libs/PHPMailer/src/Exception.php';
                require_once __DIR__ . '/../../../libs/PHPMailer/src/PHPMailer.php';
                require_once __DIR__ . '/../../../libs/PHPMailer/src/SMTP.php';

                // 2. Create local directory for temp QR storage if it doesn't exist
                $qrDir = __DIR__ . '/../../../uploads/qrcodes/';
                if (!file_exists($qrDir)) {
                    mkdir($qrDir, 0777, true);
                }

                // 3. Generate QR file path (contains only the reference number for ultra-fast scanning)
                $qrFilePath = $qrDir . $referenceNumber . '.png';
                QRcode::png($referenceNumber, $qrFilePath, QR_ECLEVEL_H, 6);

                // 4. Initialize PHPMailer Configuration
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'yeahlow24@gmail.com';  // Change to your Gmail
                $mail->Password   = 'jqtv dlyp etum ojut';    // Change to your 16-character App Password
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('YOUR_GMAIL_ACCOUNT@gmail.com', 'Walany Event Management');
                $mail->addAddress($email, $firstName . ' ' . $lastName);

                // Inline Attachment Mapping
                $mail->addEmbeddedImage($qrFilePath, 'qr_code_embed');

                // Email Content Template
                $mail->isHTML(true);
                $mail->Subject = 'Your Event Ticket Confirmation: ' . $referenceNumber;
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 8px; max-width: 500px;'>
                        <h2 style='color: #17a2b8;'>Registration Confirmed! 🎉</h2>
                        <p>Hi <strong>{$firstName}</strong>,</p>
                        <p>Thank you for registering. Below are your verification details for entrance check-in:</p>
                        <hr style='border: 0; border-top: 1px solid #eee;'>
                        <p style='font-size: 18px;'>Your Ticket Reference ID: <strong style='color: #333; letter-spacing:1px;'>{$referenceNumber}</strong></p>
                        <div style='text-align: center; margin: 20px 0;'>
                            <img src='cid:qr_code_embed' alt='Your QR Ticket Code' style='width: 200px; height: 200px;'><br>
                            <small style='color: #666;'>Present this QR code to the attendance deck webcam scanner.</small>
                        </div>
                    </div>
                ";

                $mail->send();

                // Delete the file locally after sending to save server storage space
                if (file_exists($qrFilePath)) {
                    unlink($qrFilePath);
                }

            } catch (Exception $e) {
                // Log the exception error quietly so it doesn't break the client response experience
                error_log("Mail/QR Processing Error: " . $e->getMessage());
            }
            // --- END: GENERATE QR CODE & EMAIL DELIVERY SYSTEM ---

            return [
                'status' => 'success',
                'message' => 'Registration successfully completed! Check your email for your entry pass QR code.',
                'reference_number' => $referenceNumber
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Critical storage transaction breakdown inside the model layer.'
            ];
        }
    }
}
?>