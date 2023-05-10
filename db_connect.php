<?php
// Connexion à la base de données
$servername = "localhost";
$name = "appWeb";
$password = "";
$dbname = "app_web";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $name, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}