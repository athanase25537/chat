<?php
function getUser(String $username,PDO $conn){
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username]);

    if($stmt->rowCount() === 1){
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
        $user = [];
    }
    return $user;
}