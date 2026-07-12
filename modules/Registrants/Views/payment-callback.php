<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../core/config.php';
require_once __DIR__ . '/../../../core/database.php';
require_once __DIR__ . '/../Models/RegistrantModel.php';

$status = $_GET['status'] ?? 'failed';
$referenceNumber = $_GET['ref'] ?? null; // Captures your original evaluation ID safely

if ($status === 'success' && $referenceNumber) {
    $database = new Database();
    $dbConnection = $database->getConnection();
    
    $mockGatewayReceipt = "PMGO-" . strtoupper(bin2hex(random_bytes(5)));
    
    // Updates local ledger matching exactly where reference_number equals your evaluation ID
    $stmt = $dbConnection->prepare("
        UPDATE walania_registrant
        SET payment_status = 'completed',
            payment_method = 'PayMongo_Gateway',
            payment_amount = 250.00,
            payment_reference = :receipt
        WHERE reference_id = :ref
    ");
    
    $stmt->execute([
        'receipt' => $mockGatewayReceipt,
        'ref'     => $referenceNumber
    ]);

    $_SESSION['success_timestamp'] = date('F d, Y h:i A');
    header("Location: /PHP_Project/Walany/modules/Registrants/Views/registration-success.php");
    exit();
} else {
    header("Location: /PHP_Project/Walany/index.php?module=Home&error=payment_cancelled");
    exit();
}