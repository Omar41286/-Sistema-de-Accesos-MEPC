<?php
include 'conexion_php_sqlS.php';

// Agregar Producto
if (isset($_POST['agregar_producto'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    // Inserta un nuevo producto en la base de datos
    $sql = "INSERT INTO Producto (Nombre_producto, Descripcion, Precio) VALUES (?, ?, ?)";
    $params = [$nombre_producto, $descripcion, $precio];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<div class='alert alert-success text-center'>Producto agregado correctamente.</div>";
    }

    // Libera los recursos de la consulta
    sqlsrv_free_stmt($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #0A192F;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-weight: bold;
            color: #333;
        }
        .form-group label {
            font-weight: 500;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            font-size: 1rem;
            color: #ffffff;
            text-decoration: none;
        }
        .btn-back i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="text-center">Gestión de Productos</h2>
            <a href="javascript:history.back()" class="btn btn-danger btn-back">
               <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
        </div>

        <!-- Formulario para Crear Productos -->
        <form method="POST" action="gestionar_productos.php">
            <div class="form-group mb-3">
                <label for="nombre_producto">Nombre del Producto:</label>
                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
            </div>
            <div class="form-group mb-3">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
            </div>
            <button type="submit" name="agregar_producto" class="btn btn-primary w-100">Agregar Producto</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
