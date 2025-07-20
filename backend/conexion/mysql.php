<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hotel';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Error al conectar a MySQL: " . $conn->connect_error);
}
?>
