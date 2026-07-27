<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../core/Config.php';
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../Models/RegistrantModel.php';

$status = $_GET['status'] ?? 'failed';
$referenceNumber = $_GET['ref'] ?? null; // Captures your original evaluation ID safely

if ($status === 'success' && $referenceNumber) {
    $database = new Database();
    $dbConnection = $database->getConnection();
    
    $mockGatewayReceipt = "PMGO-" . strtoupper(bin2hex(random_bytes(5)));
    
    // Updates local ledger by dynamically joining walania_event to get the exact price
    $stmt = $dbConnection->prepare("
        UPDATE walania_registrant r
        JOIN walania_event e ON r.event_id = e.id
        SET r.payment_status = 'completed',
            r.payment_method = 'PayMongo_Gateway',
            r.payment_amount = COALESCE(NULLIF(e.price, 0), 250.00),
            r.payment_reference = :receipt
        WHERE r.reference_id = :ref
    ");
    
    $stmt->execute([
        'receipt' => $mockGatewayReceipt,
        'ref'     => $referenceNumber
    ]);

    $_SESSION['success_timestamp'] = date('F d, Y h:i A');
    header("Location: /Walany/modules/Registrants/Views/registration-success.php");
    exit();
} else {
    header("Location: /Walany/index.php?module=Home&error=payment_cancelled");
    exit();
}