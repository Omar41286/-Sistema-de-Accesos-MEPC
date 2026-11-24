<?php
include 'conexion_php_sqlS.php';
session_start();

// Función para obtener el inventario de un almacén
function obtenerInventario($id_almacen) {
    global $conn;
    $sql = "SELECT p.Nombre_producto, i.Cantidad_disponible, a.Nombre AS Nombre_almacen, a.Ubicacion
            FROM Inventario i
            JOIN Producto p ON i.ID_producto = p.ID_producto
            JOIN Almacen a ON i.ID_almacen = a.ID_almacen
            WHERE i.ID_almacen = ?";
    $params = array($id_almacen);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        $_SESSION['mensaje'] = "Error al obtener el inventario: " . print_r(sqlsrv_errors(), true);
        return array();
    }
    $inventario = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $inventario[] = $row;
    }
    return $inventario;
}

// Procesar la selección del almacén
if (isset($_POST['id_almacen'])) {
    $id_almacen = intval($_POST['id_almacen']);
    $_SESSION['inventario'] = obtenerInventario($id_almacen);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Almacenes - SafeEntry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0A192F] to-[#3A4968] p-8">
    <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden bg-white/95 p-8 shadow-xl rounded-lg">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-[#0A192F]">Inventario de Almacenes</h1>
                <a href="javascript:history.back()" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <i class="bi bi-arrow-left-circle mr-2"></i>
                    Volver
                </a>
            </div>

            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="mb-4 rounded-md bg-blue-50 p-4 text-blue-700" role="alert">
                    <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="mb-8">
                <h2 class="mb-4 text-xl font-semibold">Selecciona un almacén:</h2>
                <div class="flex flex-wrap gap-2">
                    <?php
                    // Obtener lista de almacenes
                    $sql = "SELECT ID_almacen, Nombre FROM Almacen";
                    $stmt = sqlsrv_query($conn, $sql);
                    if ($stmt === false) {
                        echo "<div class='rounded-md bg-red-50 p-4 text-red-700'>Error al cargar almacenes.</div>";
                    } else {
                        while ($warehouse = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            echo "<button type='submit' name='id_almacen' value='{$warehouse['ID_almacen']}' class='rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2'>{$warehouse['Nombre']}</button>";
                        }
                        sqlsrv_free_stmt($stmt);
                    }
                    ?>
                </div>
            </form>

            <?php if (isset($_SESSION['inventario']) && !empty($_SESSION['inventario'])): ?>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Producto</th>
                                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Cantidad Disponible</th>
                                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Almacén</th>
                                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Ubicación</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                            <?php foreach ($_SESSION['inventario'] as $item): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo $item['Nombre_producto']; ?></td>
                                    <td class="px-6 py-4"><?php echo $item['Cantidad_disponible']; ?></td>
                                    <td class="px-6 py-4"><?php echo $item['Nombre_almacen']; ?></td>
                                    <td class="px-6 py-4"><?php echo $item['Ubicacion']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php unset($_SESSION['inventario']); ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>