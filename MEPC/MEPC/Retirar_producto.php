<?php
session_start();

include 'conexion_php_sqlS.php';

// Verificar que el ID_almacen esté en la sesión
if (!isset($_SESSION['id_almacen'])) {
    die("<div class='alert alert-danger text-center'>Acceso denegado. No se ha especificado un almacén.</div>");
}

$id_almacen = intval($_SESSION['id_almacen']);

// Procesar la selección de productos para retirar
if (isset($_POST['retirar_productos'])) {
    $productosSeleccionados = $_POST['productos'];
    $cantidades = $_POST['cantidades'];

    // Iterar sobre los productos seleccionados y procesar cada uno
    foreach ($productosSeleccionados as $productoID) {
        $cantidad = intval($cantidades[$productoID]);
        if ($cantidad > 0) {
            $sql = "INSERT INTO Retiros (ProductoID, Cantidad, Fecha, ID_usuario) VALUES (?, ?, GETDATE(), ?)";
            $params = [$productoID, $cantidad, $_SESSION['ID_usuario']];
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            sqlsrv_free_stmt($stmt);

            // Actualizar el inventario
            $sql_update = "UPDATE Inventario SET Cantidad_disponible = Cantidad_disponible - ? WHERE ID_producto = ?";
            $params_update = [$cantidad, $productoID];
            $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

            if ($stmt_update === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            sqlsrv_free_stmt($stmt_update);
        }
    }

    echo "<div class='alert alert-success text-center'>Productos retirados correctamente.</div>";
}

// Obtener lista de productos filtrados por el almacén
$sql = "SELECT p.ID_producto, p.Nombre_producto, p.Descripcion, p.Precio, i.Cantidad_disponible 
        FROM Producto p 
        JOIN Inventario i ON p.ID_producto = i.ID_producto
        WHERE i.ID_almacen = ?";
$params = [$id_almacen];
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retirar Productos</title>
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
            <h2 class="text-center">Retirar Productos del Almacén</h2>
            <a href="javascript:history.back()" class="btn btn-danger btn-back">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
        </div>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="p-4 rounded shadow-sm bg-light">
            <div class="mb-4 text-center">
                <h4 class="fw-bold text-primary">Selecciona los productos y cantidades a retirar:</h4>
                <p class="text-muted">Marca los productos y especifica la cantidad a retirar</p>
            </div>
            
            <div class="form-group mb-4">
    <?php while ($producto = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
        <div class="form-check mb-3 p-3 border rounded bg-white shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <input class="form-check-input me-2" type="checkbox" name="productos[]" 
                           value="<?php echo $producto['ID_producto']; ?>" 
                           id="producto<?php echo $producto['ID_producto']; ?>"
                           onchange="toggleCantidad(this, <?php echo $producto['ID_producto']; ?>)">
                    <label class="form-check-label" for="producto<?php echo $producto['ID_producto']; ?>">
                        <strong><?php echo htmlspecialchars($producto['Nombre_producto']); ?></strong> - 
                        <span class="text-muted"><?php echo htmlspecialchars($producto['Descripcion']); ?></span>
                        <span class="badge bg-success ms-2">$<?php echo number_format($producto['Precio'], 2); ?></span>
                    </label>
                </div>
            </div>
            
            <div class="mt-2">
                <label for="cantidad<?php echo $producto['ID_producto']; ?>" class="form-label text-muted small">Cantidad:</label>
                <input type="number" name="cantidades[<?php echo $producto['ID_producto']; ?>]" 
                       id="cantidad<?php echo $producto['ID_producto']; ?>" 
                       class="form-control w-50 d-inline" min="1" max="<?php echo $producto['Cantidad_disponible']; ?>" placeholder="0" disabled required>
                <span class="ms-2 text-muted">Disponible: <?php echo $producto['Cantidad_disponible']; ?></span>
            </div>
        </div>
    <?php } ?>
</div>
<button type="submit" name="retirar_productos" class="btn btn-primary w-100">
    <i class="bi bi-box-arrow-up"></i> Retirar Productos Seleccionados
</button>

<script>
function toggleCantidad(checkbox, productId) {
    var cantidadInput = document.getElementById('cantidad' + productId);
    cantidadInput.disabled = !checkbox.checked;
    if (!checkbox.checked) {
        cantidadInput.value = '';
    }
}
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Libera los recursos de la consulta
sqlsrv_free_stmt($stmt);
?>
