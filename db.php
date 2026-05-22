<?php

function walania_db(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $connection = new mysqli('localhost', 'root', '', 'walania');
    $connection->set_charset('utf8mb4');

    return $connection;
}
