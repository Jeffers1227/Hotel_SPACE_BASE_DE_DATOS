<?php
require '../conexion/mysql.php';

$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'];
$correo = $data['correo'];
$contrasena = password_hash($data['contrasena'], PASSWORD_BCRYPT);
$rol = $data['rol'] ?? 'cliente';

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $correo, $contrasena, $rol);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "✅ Usuario registrado correctamente"]);
} else {
    echo json_encode(["error" => "❌ Error: " . $stmt->error]);
}

$conn->close();
?>