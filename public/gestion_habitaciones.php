<?php
$conexion = new mysqli("localhost", "root", "", "telo");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Insertar nueva habitación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"], $_POST["precio"]) && !isset($_POST["editar_id"])) {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];

    $stmt = $conexion->prepare("INSERT INTO habitaciones (nombre, precio) VALUES (?, ?)");
    $stmt->bind_param("sd", $nombre, $precio);
    $stmt->execute();
    $stmt->close();
}

// Eliminar habitación
if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];
    $conexion->query("DELETE FROM habitaciones WHERE id = $idEliminar");
}

// Preparar para editar
$editar = null;
if (isset($_GET['editar'])) {
    $idEditar = $_GET['editar'];
    $res = $conexion->query("SELECT * FROM habitaciones WHERE id = $idEditar");
    $editar = $res->fetch_assoc();
}

// Guardar cambios de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_id"])) {
    $id = $_POST["editar_id"];
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];

    $stmt = $conexion->prepare("UPDATE habitaciones SET nombre = ?, precio = ? WHERE id = ?");
    $stmt->bind_param("sdi", $nombre, $precio, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: habitaciones.php");
    exit;
}

$resultado = $conexion->query("SELECT * FROM habitaciones");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Habitaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #83a4d4, #b6fbff);
            padding: 40px;
        }
        .container {
            background-color: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        h2 {
            color: #333;
        }
        table th {
            background-color: #0d6efd;
            color: white;
        }
        .btn-edit {
            background-color: #ffc107;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h3><a href="bienvenido.html">Regresar</a></h3>
    <h2 class="mb-4">Gestión de Habitaciones</h2>

    <form method="POST" class="row g-3 mb-4">
        <?php if ($editar): ?>
            <input type="hidden" name="editar_id" value="<?= $editar['id'] ?>">
        <?php endif; ?>

        <div class="col-md-5">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre de la habitación" required value="<?= $editar['nombre'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="precio" class="form-control" placeholder="Precio" required value="<?= $editar['precio'] ?? '' ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <?= $editar ? 'Guardar' : 'Agregar' ?>
            </button>
        </div>
        <?php if ($editar): ?>
            <div class="col-md-2">
                <a href="habitaciones.php" class="btn btn-secondary w-100">Cancelar</a>
            </div>
        <?php endif; ?>
    </form>

    <table class="table table-bordered table-hover text-center align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()) : ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                    <td>S/ <?= number_format($fila['precio'], 2) ?></td>
                    <td>
                        <a href="?editar=<?= $fila['id'] ?>" class="btn btn-sm btn-edit">Editar</a>
                        <a href="?eliminar=<?= $fila['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('¿Seguro que deseas eliminar esta habitación?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
