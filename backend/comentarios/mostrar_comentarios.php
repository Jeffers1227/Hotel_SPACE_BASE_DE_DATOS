<?php
require __DIR__ . '/../../vendor/autoload.php';

$uri = "mongodb+srv://Proyect:Jeff1234@cluster0.dtmidik.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0";

try {
    $client = new MongoDB\Client($uri);
    $database = $client->hotel;
    $collection = $database->comentarios;
} catch (Exception $e) {
    die("❌ Error al conectar a MongoDB: " . $e->getMessage());
}

$comentarios = $collection->find();

echo "<h2>📋 Comentarios de Clientes</h2>";
echo "<table border='1' cellpadding='8' cellspacing='0'>";
echo "<tr><th>Usuario</th><th>Comentario</th><th>Petición</th><th>Fecha</th></tr>";

foreach ($comentarios as $c) {
    $fecha = $c['fecha'] instanceof MongoDB\BSON\UTCDateTime ? $c['fecha']->toDateTime()->format('Y-m-d H:i') : '';
    echo "<tr>";
    echo "<td>" . htmlspecialchars($c['usuario']) . "</td>";
    echo "<td>" . htmlspecialchars($c['comentario']) . "</td>";
    echo "<td>" . htmlspecialchars($c['peticion'] ?? '-') . "</td>";
    echo "<td>" . $fecha . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
