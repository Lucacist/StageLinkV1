<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StageLink"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
