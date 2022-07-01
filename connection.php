<?php

    # SQL Params
    $host = 'localhost';
    $dbname = 'sakurahospital';
    $user = 'root';
    $password = '';
    $pdo = new PDO ("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>