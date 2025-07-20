<?php
require __DIR__ . '/../vendor/autoload.php';

$uri = "mongodb+srv://Proyect:Jeff1234@cluster0.dtmidik.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0";

try {
    $client = new MongoDB\Client($uri);
    $db = $client->selectDatabase('hotel');
    $comentarios = $db->comentarios;
} catch (Exception $e) {
    die('❌ Error al conectar a MongoDB: ' . $e->getMessage());
}
?>
