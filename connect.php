<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tietotesti";

// Yhteys
$conn = new mysqli($servername, $username, $password, $database);

// Virhetarkistus
if ($conn->connect_error) {
    die("Yhteysvirhe: " . $conn->connect_error);
}

// Aseta merkistö
$conn->set_charset("utf8mb4");
?>