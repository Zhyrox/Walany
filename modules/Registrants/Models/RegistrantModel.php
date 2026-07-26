<?php
require_once __DIR__ . '/../../../core/Database.php';

class RegistrantModel {
    private $db;
    private $errors = [];

    public function __construct(PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * Complete Domain Validation Rule Suite
     */
    public function validateRegistration(object $details): bool {
        $this->errors = [];
        $nameRegex = "/^[a-zA-ZñÑ\s\-]+$/";
        $phoneRegex = "/^09[0-9]{9}$/";

        if (empty($details->firstName)) { $this->errors[] = "First name is required."; }
        elseif (!preg_match($nameRegex, $details->firstName)) { $this->errors[] = "First name contains invalid characters."; }

        if (!empty($details->middleName) && !preg_match($nameRegex, $details->middleName)) { $this->errors[] = "Middle name contains invalid characters."; }

        if (empty($details->lastName)) { $this->errors[] = "Last name is required."; }
        elseif (!preg_match($nameRegex, $details->lastName)) { $this->errors[] = "Last name contains invalid characters."; }

        if (empty($details->phone)) { $this->errors[] = "Phone number is required."; }
        elseif (!preg_match($phoneRegex, $details->phone)) { $this->errors[] = "Phone number must be a valid 11-digit string starting with 09."; }

        if (!$details->email) { 
            $this->errors[] = "Please provide a valid email address.";
        } else {
            $disallowedDomains = ['example.com', 'test.com', 'domain.com', 'mailinator.com', 'yopmail.com'];
            $emailParts = explode('@', $details->email);
            $domain = strtolower(end($emailParts));

            if (in_array($domain, $disallowedDomains)) {
                $this->errors[] = "Registration blocked: Real-world email addresses only.";
            } elseif (!checkdnsrr($domain, 'MX')) {
                $this->errors[] = "The email domain provided does not appear to host a valid mail server.";
            }
        }

        return empty($this->errors);
    }

    /**
     * Evaluates Security State Throttling Policies
     */
    public function checkOtpLockoutState(string $email): array {
        $currentTime = date('Y-m-d H:i:s');
        $latestOtpLog = $this->getLatestOtpLog($email);

        if ($latestOtpLog) {
            // 1. Check if the user is currently under a hard failed-attempt lockout
            if ($latestOtpLog['locked_until'] && strtotime($latestOtpLog['locked_until']) > strtotime($currentTime)) {
                $timeLeft = ceil((strtotime($latestOtpLog['locked_until']) - strtotime($currentTime)) / 60);
                return ['status' => 'error', 'message' => "Too many verification failures. Locked for {$timeLeft} minutes."];
            }

            // 2. TIME-BASED HOURLY RESET CHECK
            // Check if the latest OTP log was created within the last 60 minutes
            $logCreatedAt = strtotime($latestOtpLog['created_at'] ?? $latestOtpLog['expires_at'] . ' -5 minutes');
            $oneHourAgo = strtotime('-1 hour');

            if ($logCreatedAt > $oneHourAgo) {
                // Log was within the past hour — enforce hourly limit
                if ((int)$latestOtpLog['resend_count_hourly'] >= 6) {
                    return ['status' => 'error', 'message' => 'Hourly security request threshold reached. Please wait an hour.'];
                }
            } else {
                // More than an hour has passed! Reset the hourly counter on the log object
                $latestOtpLog['resend_count_hourly'] = 0;
            }
        }

        return ['status' => 'success', 'latest_log' => $latestOtpLog];
    }

    public function processOtpResendBackoff(string $email, int $timeSinceLastRequest, int $currentCooldown): array {
        if ($timeSinceLastRequest < $currentCooldown) {
            return ['status' => 'error', 'message' => 'Please wait ' . ($currentCooldown - $timeSinceLastRequest) . ' seconds.'];
        }

        $newCooldown = ($currentCooldown == 30) ? 60 : 300;
        $newPin = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $newExpiration = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $latestLog = $this->getLatestOtpLog($email);
        $hourlyResends = $latestLog ? (int)$latestLog['resend_count_hourly'] + 1 : 1;

        $this->createOtpLog($email, $newPin, $hourlyResends, $newExpiration);

        return ['status' => 'success', 'new_pin' => $newPin, 'new_cooldown' => $newCooldown];
    }

    public function incrementOtpAttempts(array $activeOtpRecord): array {
        $newAttemptsCount = (int)$activeOtpRecord['attempts'] + 1;
        if ($newAttemptsCount >= 3) {
            $lockoutUntilTime = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $this->updateOtpAttempts($activeOtpRecord['id'], $newAttemptsCount, $lockoutUntilTime);
            return ['status' => 'error', 'message' => 'Too many failed entries. Try again in 15 minutes.'];
        }
        
        $this->updateOtpAttempts($activeOtpRecord['id'], $newAttemptsCount);
        $triesRemaining = 3 - $newAttemptsCount;
        return ['status' => 'error', 'message' => "Incorrect code. {$triesRemaining} attempts remaining."];
    }

    public function generateReferenceToken(): string {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $part1 = ''; $part2 = '';
        for ($i = 0; $i < 4; $i++) {
            $part1 .= $letters[random_int(0, strlen($letters) - 1)];
            $part2 .= $numbers[random_int(0, strlen($numbers) - 1)];
        }
        return $part1 . '-' . $part2;
    }

    public function save(array $data): ?string {
        try {
            $isUnique = false;
            $referenceId = '';
            while (!$isUnique) {
                $referenceId = $this->generateReferenceToken();
                $checkStmt = $this->db->prepare("SELECT COUNT(*) FROM `walania_registrant` WHERE `reference_id` = :ref");
                $checkStmt->execute([':ref' => $referenceId]);
                if ((int)$checkStmt->fetchColumn() === 0) { $isUnique = true; }
            }

            $sql = "INSERT INTO `walania_registrant` (`event_id`, `reference_id`, `first_name`, `middle_name`, `last_name`, `email`, `contact_number`, `is_verified`) VALUES (:event_id, :reference_id, :first_name, :middle_name, :last_name, :email, :contact_number, :is_verified)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':event_id'       => $data['event_id'],
                ':reference_id'   => $referenceId,
                ':first_name'     => $data['first_name'],
                ':middle_name'    => !empty($data['middle_name']) ? $data['middle_name'] : null,
                ':last_name'      => $data['last_name'],
                ':email'          => $data['email'],
                ':contact_number' => $data['contact_number'],
                ':is_verified'    => (int)$data['is_verified']
            ]);
            return $referenceId;
        } catch (PDOException $e) {
            // 1. Log it locally to identify which model query failed
            error_log("MODEL ENGINE FAILURE: " . $e->getMessage());
            
            // 2. Re-throw it so the Controller's catch block can catch it and redirect the user
            throw $e;
        }
    }

    public function getEventData(int $eventId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `walania_event` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $eventId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getLatestOtpLog(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `walania_otp_logs` WHERE `email` = ? ORDER BY `id` DESC LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createOtpLog(string $email, string $pin, int $hourlyResends, string $expiresAt): bool {
        $stmt = $this->db->prepare("INSERT INTO `walania_otp_logs` (`email`, `otp_code`, `attempts`, `resend_count_hourly`, `expires_at`) VALUES (?, ?, 0, ?, ?)");
        return $stmt->execute([$email, $pin, $hourlyResends, $expiresAt]);
    }

    public function updateOtpAttempts(int $logId, int $attempts, ?string $lockedUntil = null): bool {
        if ($lockedUntil) {
            $stmt = $this->db->prepare("UPDATE `walania_otp_logs` SET `attempts` = ?, `locked_until` = ? WHERE `id` = ?");
            return $stmt->execute([$attempts, $lockedUntil, $logId]);
        }
        $stmt = $this->db->prepare("UPDATE `walania_otp_logs` SET `attempts` = ? WHERE `id` = ?");
        return $stmt->execute([$attempts, $logId]);
    }

    public function verifyRegistrant(string $referenceId): bool {
        $stmt = $this->db->prepare("UPDATE `walania_registrant` SET `is_verified` = 1 WHERE `reference_id` = ?");
        return $stmt->execute([$referenceId]);
    }

    public function getRegistrantByRef(string $referenceId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `walania_registrant` WHERE `reference_id` = ? LIMIT 1");
        $stmt->execute([$referenceId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Verifies if the selected event has open slots remaining.
     *
     * @param int $eventId
     * @return bool True if slots are available, false if full
     */
    public function isEventCapacityAvailable(int $eventId): bool
    {
        // 1. Get max capacity for the event
        $stmt = $this->db->prepare("SELECT max_capacity FROM walania_event WHERE id = :event_id LIMIT 1");
        $stmt->execute(['event_id' => $eventId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $maxCapacity = $event ? (int)$event['max_capacity'] : 100;

        // 2. Count current successful registrations
        $stmt = $this->db->prepare("SELECT COUNT(*) as current_count FROM walania_registrant WHERE event_id = :event_id");
        $stmt->execute(['event_id' => $eventId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $currentCount = $result ? (int)$result['current_count'] : 0;

        // Return true if there is still room left
        return $currentCount < $maxCapacity;
    }

    /**
     * Processes traffic throttling for the virtual waiting room.
     *
     * @param int $eventId
     * @param string $sessionId
     * @param int $maxActiveUsers How many users allowed to access the form at once
     * @return string 'active' if allowed to form, 'waiting' if held in waiting room
     */
    public function manageTrafficQueue(int $eventId, string $sessionId, int $maxActiveUsers = 5): string
    {
        // 1. Clean up expired sessions (People who closed the tab or abandoned the form after 5 minutes)
        $this->db->prepare("DELETE FROM walania_registration_queue WHERE last_activity < NOW() - INTERVAL 5 MINUTE")->execute();

        // 2. Register or update the current user's presence in the queue
        $stmt = $this->db->prepare("INSERT INTO walania_registration_queue (session_id, event_id, status)
                                    VALUES (:session_id, :event_id, 'waiting')
                                    ON DUPLICATE KEY UPDATE last_activity = CURRENT_TIMESTAMP");
        $stmt->execute(['session_id' => $sessionId, 'event_id' => $eventId]);

        // 3. Check if the user is already marked as active
        $stmt = $this->db->prepare("SELECT status FROM walania_registration_queue WHERE session_id = :session_id LIMIT 1");
        $stmt->execute(['session_id' => $sessionId]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($currentUser && $currentUser['status'] === 'active') {
            return 'active';
        }

        // 4. Count how many slots are currently being used on the form
        $stmt = $this->db->prepare("SELECT COUNT(*) as active_count FROM walania_registration_queue WHERE event_id = :event_id AND status = 'active'");
        $stmt->execute(['event_id' => $eventId]);
        $activeCount = ($stmt->fetch(PDO::FETCH_ASSOC))['active_count'] ?? 0;

        // 5. If there is room available, promote this user from 'waiting' to 'active'
        if ($activeCount < $maxActiveUsers) {
            // Double check they are the next inline by sorting by joined_at
            $stmt = $this->db->prepare("SELECT session_id FROM walania_registration_queue WHERE event_id = :event_id AND status = 'waiting' ORDER BY joined_at ASC LIMIT 1");
            $stmt->execute(['event_id' => $eventId]);
            $nextInLine = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($nextInLine && $nextInLine['session_id'] === $sessionId) {
                $update = $this->db->prepare("UPDATE walania_registration_queue SET status = 'active' WHERE session_id = :session_id");
                $update->execute(['session_id' => $sessionId]);
                return 'active';
            }
        }

        return 'waiting';
    }

    /**
     * Gets the user's specific position number in the line.
     */
    public function getTrafficLinePosition(int $eventId, string $sessionId): int
    {
        $stmt = $this->db->prepare("SELECT joined_at FROM walania_registration_queue WHERE session_id = :session_id LIMIT 1");
        $stmt->execute(['session_id' => $sessionId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) return 1;

        $stmt = $this->db->prepare("SELECT COUNT(*) as line_position FROM walania_registration_queue WHERE event_id = :event_id AND status = 'waiting' AND joined_at <= :user_time");
        $stmt->execute(['event_id' => $eventId, 'user_time' => $user['joined_at']]);
        return ($stmt->fetch(PDO::FETCH_ASSOC))['line_position'] ?? 1;
    }

    public function completePaymentRecord(string $referenceNumber, string $receipt, float $amount = 250.00): bool
    {
        $stmt = $this->db->prepare("
            UPDATE walania_registrant
            SET payment_status = 'completed',
                payment_method = 'PayMongo_Gateway',
                payment_amount = :amount,
                payment_reference = :receipt
            WHERE reference_id = :ref
        ");
        
        return $stmt->execute([
            'amount'  => $amount,
            'receipt' => $receipt,
            'ref'     => $referenceNumber
        ]);
    }
}