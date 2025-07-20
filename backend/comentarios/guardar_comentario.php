<?php
require __DIR__ . '/../../vendor/autoload.php';

$usuario = $_GET['usuario'] ?? null;
$comentario = $_GET['comentario'] ?? null;
$peticion = $_GET['peticion'] ?? null;

if (!$usuario || !$comentario) {
    die("❌ Faltan datos obligatorios.");
}

$uri = "mongodb+srv://Proyect:Jeff1234@cluster0.dtmidik.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0";

try {
    $client = new MongoDB\Client($uri);
    $database = $client->hotel;
    $collection = $database->comentarios;
////////////////////
    $documento = [
    'usuario' => [
        'nombre' => $_GET['usuario'],
        'correo' => $_GET['correo']
    ],
    'comentario' => $_GET['comentario'],
    'peticion' => $_GET['peticion'],
    'fecha' => new MongoDB\BSON\UTCDateTime()
];

///////////////////
    $resultado = $collection->insertOne($documento);
    echo "✅ Comentario guardado correctamente con ID: " . $resultado->getInsertedId();
} catch (Exception $e) {
    echo "❌ Error al guardar comentario: " . $e->getMessage();
}
?>
