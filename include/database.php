<?php
    $host = '10.6.0.2';
    $username = 'loramonitor';
    $password = 'loramonitor';
    $database = 'loramonitor';

    try {
        $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOExecption $error) {
        die("Connection failed: " . $error->getMessage());
    }
?>