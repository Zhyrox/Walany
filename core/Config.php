<?php
// DYNAMIC BASE URL ENGINE
// Automatically calculates the folder path from htdocs, no matter how deeply nested it is!
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseDir = preg_replace('#/modules/.*|/core.*#', '', $scriptDir);
$baseUrl = rtrim($baseDir, '/') . '/';

$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comment lines
        if (strpos(trim($line), '#') === 0) continue;
        
        // Split by the first '=' character found
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
        }
    }
}

define('BASE_URL', $baseUrl);

define('DB_HOST',   getenv('DB_HOST')   ?: '127.0.0.1');
define('DB_NAME',   getenv('DB_NAME')   ?: 'walania');
define('DB_PORT',   getenv('DB_PORT')   ?: '3306');
define('DB_CHAR',   getenv('DB_CHAR')   ?: 'utf8mb4');
define('DB_USER',   getenv('DB_USER')   ?: 'root');
define('DB_PASS',   getenv('DB_PASS')   ?: '');

define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
?>