<?php
$host = "localhost";
$dbname = "marketplace";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connexion reuissit !";
} catch (PDOException $error) {
    die("Erreur SQL : " . $error->getMessage());
}