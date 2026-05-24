<?php
require_once __DIR__ . '/auth.php';

// Logs out the user
logout_user();

// Redirect to index.php
header('Location: ../views/index.php#home');
exit;
?>