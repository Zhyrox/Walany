<?php
// core/migrate.php
require_once __DIR__ . '/Database.php';

try {
    $dbInstance = new Database();
    $db = $dbInstance->getConnection();
    
    // Ensure PDO throws explicit exceptions on database-level errors
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Walania Architectural Migration Engine</h2>";
    echo "Starting database updates...<br><br>";

    // ==========================================
    // PHASE 1: WALANIA_REGISTRANT RESTRUCTURING
    // ==========================================
    
    // 1. Check for legacy full_name column to prevent duplicate alterations
    $checkColumn = $db->query("SHOW COLUMNS FROM walania_registrant LIKE 'full_name'");
    $oldColumnExists = $checkColumn->rowCount() > 0;

    if ($oldColumnExists) {
        echo " -> Found legacy 'full_name' column. Restructuring table data lanes...<br>";
        $sql = "ALTER TABLE walania_registrant
                ADD COLUMN first_name VARCHAR(100) NOT NULL AFTER id,
                ADD COLUMN middle_name VARCHAR(100) NULL AFTER first_name,
                ADD COLUMN last_name VARCHAR(100) NOT NULL AFTER middle_name,
                DROP COLUMN full_name";
        $db->exec($sql);
        echo "<strong>SUCCESS:</strong> Table 'walania_registrant' normalized into atomic name components.<br>";
    } else {
        echo " -> Table 'walania_registrant' name columns are already up to date.<br>";
    }

    // 2. Add reference_number column if it doesn't exist yet
    $checkRefColumn = $db->query("SHOW COLUMNS FROM walania_registrant LIKE 'reference_number'");
    if ($checkRefColumn->rowCount() === 0) {
        echo " -> Appending clean transactional 'reference_number' lane...<br>";
        $db->exec("ALTER TABLE walania_registrant ADD COLUMN reference_number VARCHAR(9) NOT NULL AFTER id");
        
        // AUTO-FILL PRE-EXISTING RECORDS: Prevent duplicate key constraint crashes on empty rows
        echo " -> Backfilling pre-existing rows with unique dummy tokens...<br>";
        $stmt = $db->query("SELECT id FROM walania_registrant");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as $index => $row) {
            $seedToken = "SEED-" . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $updateStmt = $db->prepare("UPDATE walania_registrant SET reference_number = :ref WHERE id = :id");
            $updateStmt->execute([':ref' => $seedToken, ':id' => $row['id']]);
        }

        echo " -> Enforcing high-speed unique index locking...<br>";
        $db->exec("ALTER TABLE walania_registrant ADD UNIQUE INDEX idx_unique_ref (reference_number)");
        echo "<strong>SUCCESS:</strong> Added tracking token support to registrants.<br>";
    } else {
        echo " -> Token support already initialized in 'walania_registrant'.<br>";
    }


    // ==========================================
    // PHASE 2: WALANIA_EVENT_FEEDBACK ALTERATION
    // ==========================================
    
    // Check if the legacy user_id lane is still around to be altered
    $checkFeedbackUser = $db->query("SHOW COLUMNS FROM walania_event_feedback LIKE 'user_id'");
    
    if ($checkFeedbackUser->rowCount() > 0) {
        echo "<br>Updating 'walania_event_feedback' table architecture...<br>";
        
        // A. Drop the legacy foreign key relational constraint safely
        try {
            echo " -> Severing legacy foreign constraint link (fk_feedback_user)...<br>";
            $db->exec("ALTER TABLE walania_event_feedback DROP FOREIGN KEY fk_feedback_user");
        } catch (\Exception $e) {
            // If it already dropped on a previous attempt, gracefully continue without crashing
            echo " -> Relational constraint already severed or missing. Proceeding...<br>";
        }
        
        // B. Re-map column definitions cleanly to hold your ABCD-1234 strings (comment matches form specifications)
        echo " -> Altering database data lanes to support Reference Tokens...<br>";
        $alterFeedbackSql = "ALTER TABLE walania_event_feedback 
            CHANGE user_id reference_number VARCHAR(9) NOT NULL,
            MODIFY COLUMN event_id INT(11) NOT NULL,
            MODIFY COLUMN comment TEXT NOT NULL,
            MODIFY COLUMN rating INT(11) NOT NULL";
            
        $db->exec($alterFeedbackSql);
        
        // C. Build a high-speed engine search index for scanning references quickly
        echo " -> Optimizing lookup indices for tokens...<br>";
        $db->exec("ALTER TABLE walania_event_feedback ADD INDEX idx_ref_num (reference_number)");
        
        echo "<strong>SUCCESS:</strong> Migrated feedback schema to match structural token models.<br>";
    } else {
        echo "<br> -> Table 'walania_event_feedback' is already normalized to reference tokens.<br>";
    }

    echo "<br><strong>Migration Complete!</strong> Your local database layout completely matches the repository state.";

} catch (\Exception $e) {
    die("<br><span style='color:red;'><strong>Migration Failed:</strong></span> " . $e->getMessage());
}
?>