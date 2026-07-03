<?php
require_once __DIR__ . '/core/Database.php';

try {
    $db = (new Database())->getConnection();
    
    // Clear out old mismatched test records
    $db->exec("TRUNCATE TABLE `walania_managers`");
    
    // Generate a pristine, deterministic hash for 'Password123!'
    $cleanHash = password_hash('Password123!', PASSWORD_BCRYPT);
    
    $stmt = $db->prepare("INSERT INTO `walania_managers` (`first_name`, `last_name`, `email`, `password_hash`) VALUES ('Admin', 'System', 'admin@walany.edu.ph', :hash)");
    $stmt->execute(['hash' => $cleanHash]);
    
    echo "<h2 style='color:green;'>✔️ Database Seeded Safely!</h2>";
    echo "<p>Email: <strong>admin@walany.edu.ph</strong></p>";
    echo "<p>Password: <strong>Password123!</strong></p>";
    echo "<p>Delete this file (`hash_fix.php`) after running it for security purposes.</p>";
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Error seeding table: " . $e->getMessage() . "</h2>";
}