<?php

try {

    $pdo = new PDO(
        "mysql:host=localhost;dbname=walania;charset=utf8mb4",
        "root",
        ""
    );

    echo "Connected successfully";

} catch (PDOException $e) {

    echo $e->getMessage();
}