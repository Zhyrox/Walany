<?php
//adjust paths to match your file structure
require_once __DIR__ . '/Database.php';

try {
    // 1. Instantiate your actual Database class
    $dbInstance = new Database();
    
    // 2. Extract the PDO handle using your native getConnection method
    $db = $dbInstance->getConnection();
    
    echo "<h2>Walania Architectural Migration Engine</h2>";
    echo "Starting database updates...<br><br>";

    // 3. Check if the old 'full_name' column still exists to prevent duplicate structural modifications
    $checkColumn = $db->query("SHOW COLUMNS FROM walania_registrant LIKE 'full_name'");
    $oldColumnExists = $checkColumn->rowCount() > 0;

    if ($oldColumnExists) {
        echo "Found legacy 'name' column. Restructuring table data lanes...<br>";
        
        // Execute atomic name splits
        $sql = "ALTER TABLE walania_registrant
                ADD COLUMN first_name VARCHAR(100) NOT NULL AFTER id,
                ADD COLUMN middle_name VARCHAR(100) NULL AFTER first_name,
                ADD COLUMN last_name VARCHAR(100) NOT NULL AFTER middle_name,
                DROP COLUMN full_name";
                
        $db->exec($sql);
        echo "SUCCESS: Table 'registrants' normalized into atomic components.<br>";
    } else {
        echo "Table 'walania_registrant' is already up to date. No structural changes needed.<br>";
    }

    echo "<br><strong>Migration Complete!</strong> Your local environment matches the current repository state.";

} catch (\Exception $e) {
    // Catches both regular runtime exceptions and your custom connection bubbles
    die("<br><strong>Migration Failed:</strong> " . $e->getMessage());
}
?>