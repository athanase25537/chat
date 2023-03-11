<?php

// server name
$sName = 'localhost';
// user name
$uName = 'root';
// password
$pass = '';

// database name
$dbName = 'chat_app_db';

// creating database connection
try{
    $conn = new PDO(
        'mysql:host=localhost;dbname=chat_app_db',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
    $e->getMessage();
    echo $e;
}