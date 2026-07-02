<?php
// Database Config
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'walania');
define('DB_PORT', '3307'); //<-- 3307 yung port nung sa pc ko palitan nyo nlng if ever (CHECK PORT BY GOING TO XAMPP -> CONFIG -> SERVICE AND PORT SETTINGS -> MYSQL)
define('DB_CHAR', 'utf8mb4');
define('DB_USER', 'root');
define('DB_PASS', '');

// DYNAMIC BASE URL ENGINE
// Automatically calculates the folder path from htdocs, no matter how deeply nested it is!
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseDir = preg_replace('#/modules/.*|/core.*#', '', $scriptDir);
$baseUrl = rtrim($baseDir, '/') . '/';

define('BASE_URL', $baseUrl);
?>

