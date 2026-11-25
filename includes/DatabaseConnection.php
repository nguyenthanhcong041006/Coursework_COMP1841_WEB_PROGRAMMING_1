<?php

$db_host = 'localhost';
$db_port = '3307';                  // mysql port 
$db_name = 'coursework';            // target database name
$db_user = 'root';                  // mysql username
$db_pass = '12345aA@';              // mysql password

// initialize PDO connection with UTF-8mb4 and exception error mode
$connection = new PDO(
    "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4",
    $db_user,
    $db_pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
