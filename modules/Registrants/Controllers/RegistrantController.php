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
            return [
                'status' => 'success',
                'message' => 'Registration successfully completed!',
                'reference_number' => $saveSuccess // Passes the ABCD-1234 key forward to the user interface
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