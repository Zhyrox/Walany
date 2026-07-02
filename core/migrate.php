<?php
// core/migrate.php
require_once __DIR__ . '/Database.php';

try {
    $dbInstance = new Database();
    $db = $dbInstance->getConnection();
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Walania Schema Modernization & Migration Engine</h2>";
    echo "Synchronizing system data pools to the target release blueprint...<br><br>";

    // Disable foreign keys temporarily during structural alteration phases
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // ========================================================
    // PRE-EMPTIVE CLEANUP: Drop problematic relational locks first
    // ========================================================
    echo "-> Clearing structural relational constraints to allow alterations...<br>";
    try { $db->exec("ALTER TABLE `walania_attendance` DROP FOREIGN KEY `fk_attendance_event`, DROP FOREIGN KEY `fk_attendance_registrant`;"); } catch(\Exception $e){}
    try { $db->exec("ALTER TABLE `walania_event_feedback` DROP FOREIGN KEY `fk_feedback_event`, DROP FOREIGN KEY `fk_feedback_user`;"); } catch(\Exception $e){}
    try { $db->exec("ALTER TABLE `walania_registrant` DROP FOREIGN KEY `fk_registrant_event`, DROP FOREIGN KEY `walania_registrant_ibfk_1`, DROP FOREIGN KEY `walania_registrant_ibfk_2`;"); } catch(\Exception $e){}

    // Wipe any legacy variants of the updated constraint naming convention
    try { $db->exec("ALTER TABLE `walania_attendance` DROP FOREIGN KEY `fk_attendance_event_rel`, DROP FOREIGN KEY `fk_attendance_registrant_rel`;"); } catch(\Exception $e){}
    try { $db->exec("ALTER TABLE `walania_event_feedback` DROP FOREIGN KEY `fk_feedback_event_rel`;"); } catch(\Exception $e){}
    try { $db->exec("ALTER TABLE `walania_registrant` DROP FOREIGN KEY `fk_registrant_event_rel`;"); } catch(\Exception $e){}

    // ========================================================
    // 1. RE-ALIGN USERS TABLE
    // ========================================================
    echo "-> Verifying table 'walania_user'...<br>";
    $db->exec("CREATE TABLE IF NOT EXISTS `walania_user` (
        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `username` varchar(100) NOT NULL,
        `password` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `role` enum('admin','user') NOT NULL DEFAULT 'user',
        PRIMARY KEY (`id`),
        UNIQUE KEY `username` (`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    // ========================================================
    // 2. RE-ALIGN EVENTS TABLE
    // ========================================================
    echo "-> Verifying table 'walania_event'...<br>";
    $db->exec("CREATE TABLE IF NOT EXISTS `walania_event` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `event_date` date NOT NULL,
        `location` varchar(1000) NOT NULL,
        `description` varchar(1000) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    // Inject system default event seeds if missing
    $checkEventSeed = $db->query("SELECT COUNT(*) FROM `walania_event` WHERE `id` = 1");
    if ($checkEventSeed->fetchColumn() == 0) {
        $db->exec("INSERT INTO `walania_event` (`id`, `name`, `event_date`, `location`, `description`) VALUES
        (1, 'System Integration Seminar', '2026-08-15', 'Campus Auditorium', 'Mastering multi-tier application architectures and secure pipeline integrations.'),
        (2, 'Web Engineering Workshop', '2026-09-05', 'IT Lab 3', 'Hands-on development exercises scaling native PHP database engines.');");
        echo "   [Seeded standard event matrices successfully.]<br>";
    }

    // ========================================================
    // 3. TRANSFORM AND UPGRADE REGISTRANTS SAFELY
    // ========================================================
    echo "-> Configuring table 'walania_registrant'...<br>";
    
    // Create the base table if it doesn't exist yet
    $db->exec("CREATE TABLE IF NOT EXISTS `walania_registrant` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `reference_id` varchar(9) NOT NULL,
        `first_name` varchar(100) NOT NULL,
        `middle_name` varchar(100) DEFAULT NULL,
        `last_name` varchar(100) NOT NULL,
        `age` tinyint(3) UNSIGNED NOT NULL,
        `email` varchar(160) NOT NULL,
        `contact_number` varchar(40) NOT NULL,
        `preference_allergy` varchar(500) DEFAULT NULL,
        `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `event_id` int(11) DEFAULT NULL,
        `user_id` int(10) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    // Check if we are upgrading from the legacy structure
    $checkFullName = $db->query("SHOW COLUMNS FROM `walania_registrant` LIKE 'full_name'");
    if ($checkFullName->rowCount() > 0) {
        echo "   [Legacy full_name format string detected. Initializing data extraction...]<br>";

        // Read out old rows so we can safely compute clean split names before dropping the column
        $oldRowsStmt = $db->query("SELECT id, full_name FROM walania_registrant");
        $oldRows = $oldRowsStmt->fetchAll(PDO::FETCH_ASSOC);

        // Add the required atomic column attributes safely (Now constraint-free)
        $db->exec("ALTER TABLE `walania_registrant` 
            ADD COLUMN `reference_id` varchar(9) NOT NULL AFTER `id`,
            ADD COLUMN `first_name` varchar(100) NOT NULL AFTER `reference_id`,
            ADD COLUMN `middle_name` varchar(100) DEFAULT NULL AFTER `first_name`,
            ADD COLUMN `last_name` varchar(100) NOT NULL AFTER `middle_name`");

        // Parse names and save back immediately
        $updateNameStmt = $db->prepare("UPDATE walania_registrant SET first_name = :f, last_name = :l WHERE id = :id");
        foreach ($oldRows as $row) {
            $parts = explode(' ', trim($row['full_name']));
            if (count($parts) > 1) {
                $lastName = array_pop($parts);
                $firstName = implode(' ', $parts);
            } else {
                $firstName = $row['full_name'];
                $lastName = 'N/A';
            }
            $updateNameStmt->execute([':f' => $firstName, ':l' => $lastName, ':id' => $row['id']]);
        }

        // Safe cleanup drop
        $db->exec("ALTER TABLE `walania_registrant` DROP COLUMN `full_name`");
        echo "   [Success: Converted single text names to explicit atomic entries.]<br>";
    }

    // Safety sweep: Ensure blank reference IDs on migrated legacy rows get fixed
    $db->exec("UPDATE `walania_registrant` SET `reference_id` = CONCAT('LEG-', `id`) WHERE `reference_id` = '' OR `reference_id` IS NULL;");

    // ========================================================
    // 4. MIGRATE FEEDBACK SYSTEM STRUCTURE
    // ========================================================
    echo "-> Configuring table 'walania_event_feedback'...<br>";
    $db->exec("CREATE TABLE IF NOT EXISTS `walania_event_feedback` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `reference_id` varchar(9) NOT NULL,
        `event_id` int(11) NOT NULL,
        `comment` text NOT NULL,
        `rating` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_ref_num` (`reference_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $checkFeedbackUser = $db->query("SHOW COLUMNS FROM `walania_event_feedback` LIKE 'user_id'");
    if ($checkFeedbackUser->rowCount() > 0) {
        echo "   [Altering database layout for feedback module...]<br>";
        $db->exec("ALTER TABLE `walania_event_feedback` CHANGE COLUMN `user_id` `reference_id` varchar(9) NOT NULL;");
        $db->exec("ALTER TABLE `walania_event_feedback` MODIFY COLUMN `comment` text NOT NULL;");
        $db->exec("ALTER TABLE `walania_event_feedback` MODIFY COLUMN `rating` int(11) NOT NULL;");
    }

    // ========================================================
    // 5. RE-ALIGN ATTENDANCE REGISTRIES
    // ========================================================
    echo "-> Configuring table 'walania_attendance'...<br>";
    $db->exec("CREATE TABLE IF NOT EXISTS `walania_attendance` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `registrant_id` int(11) NOT NULL,
        `event_id` int(11) NOT NULL,
        `attendance_status` enum('present','absent','late','n/a') DEFAULT 'n/a',
        `time_checked_in` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    // ========================================================
    // 6. REBUILD FOREIGN KEY CONSTRAINTS WITH NEW DISTINCT IDENTIFIERS
    // ========================================================
    echo "-> Rebuilding system internal relations...<br>";
    
    // We append '_rel' to completely bypass internal system namespace collisions
    $db->exec("ALTER TABLE `walania_attendance` ADD CONSTRAINT `fk_attendance_event_rel` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE;");
    $db->exec("ALTER TABLE `walania_attendance` ADD CONSTRAINT `fk_attendance_registrant_rel` FOREIGN KEY (`registrant_id`) REFERENCES `walania_registrant` (`id`) ON DELETE CASCADE;");
    $db->exec("ALTER TABLE `walania_event_feedback` ADD CONSTRAINT `fk_feedback_event_rel` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE;");
    $db->exec("ALTER TABLE `walania_registrant` ADD CONSTRAINT `fk_registrant_event_rel` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE;");

    // Restore key balance tracking
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "<br><strong>Migration Complete!</strong> All structures synced perfectly.";

} catch (\Exception $e) {
    echo "<br><span style='color:red;'><strong>Migration Failure:</strong></span> " . $e->getMessage();
}
?>