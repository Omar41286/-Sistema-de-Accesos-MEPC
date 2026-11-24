<?php
include 'conexion_php_sqlS.php';
session_start();
date_default_timezone_set('America/Mexico_City');

// Función para registrar el acceso
function registrarAcceso($id_usuario, $id_almacen) {
    global $conn;
    $fecha_acceso = date('Y-m-d');
    $hora_acceso = date('H:i:s');

    $id_usuario = intval($id_usuario);
    $id_almacen = intval($id_almacen);

    if ($id_usuario <= 0 || $id_almacen <= 0) {
        $_SESSION['mensaje'] = "ID de usuario o ID de almacén no válidos.";
        return false;
    }

    $sql_verificar = "SELECT ID_usuario FROM Usuario WHERE ID_usuario = ?";
    $params_verificar = array($id_usuario);
    $stmt_verificar = sqlsrv_query($conn, $sql_verificar, $params_verificar);

    if ($stmt_verificar === false || !sqlsrv_has_rows($stmt_verificar)) {
        $_SESSION['mensaje'] = "El usuario con ID $id_usuario no existe.";
        return false;
    }

    $sql_insertar = "INSERT INTO Acceso (ID_usuario, ID_almacen, Fecha, Hora) VALUES (?, ?, ?, ?)";
    $params_insertar = array($id_usuario, $id_almacen, $fecha_acceso, $hora_acceso);
    $stmt_insertar = sqlsrv_query($conn, $sql_insertar, $params_insertar);

    if ($stmt_insertar === false) {
        $_SESSION['mensaje'] = "Error al registrar el acceso: " . print_r(sqlsrv_errors(), true);
        return false;
    }

    return true;
}

// Función para obtener el inventario
function obtenerInventario($id_almacen) {

    if (isset($_POST['id_almacen'])) {
        $_SESSION['id_almacen'] = intval($_POST['id_almacen']);
    }

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

// Procesar el escaneo del QR
if (isset($_POST['qr_code']) && isset($_POST['id_almacen'])) {
    $qr_code = $_POST['qr_code'];
    $id_almacen = intval($_POST['id_almacen']);
     
    if (empty($id_almacen)) {
        $_SESSION['mensaje'] = "El ID de almacén no se proporcionó o no es válido.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    if (preg_match('/ID_usuario:(\d+)/', $qr_code, $matches)) {
        $id_usuario = intval($matches[1]);
        if (registrarAcceso($id_usuario, $id_almacen)) {
            $_SESSION['inventario'] = obtenerInventario($id_almacen);
            $_SESSION['mensaje'] = "Acceso registrado correctamente.";
        }
    } else {
        $_SESSION['mensaje'] = "Código QR no válido.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario - SafeEntry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0A192F] to-[#3A4968] p-8">
    <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden bg-white/95 p-8 shadow-xl rounded-lg">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-[#0A192F]">Gestión de Inventario</h1>
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

            <div class="mb-8">
                <h2 class="mb-4 text-xl font-semibold">Selecciona un almacén:</h2>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $sql = "SELECT ID_almacen, Nombre FROM Almacen";
                    $stmt = sqlsrv_query($conn, $sql);
                    if ($stmt === false) {
                        echo "<div class='rounded-md bg-red-50 p-4 text-red-700'>Error al cargar almacenes.</div>";
                    } else {
                        while ($warehouse = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            echo "<button onclick='selectWarehouse({$warehouse['ID_almacen']})' class='inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2'>";
                            echo "<i class='bi bi-building mr-2'></i>{$warehouse['Nombre']}";
                            echo "</button>";
                        }
                        sqlsrv_free_stmt($stmt);
                    }
                    ?>
                </div>
            </div>

            <div id="reader" class="mb-8 h-[400px] w-full max-w-[400px] mx-auto overflow-hidden rounded-lg border-2 border-blue-500" style="display: none;"></div>

            <form id="qrForm" method="POST" style="display: none;">
                <input type="hidden" name="qr_code" id="qr_code">
                <input type="hidden" name="id_almacen" id="id_almacen">
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
            <td class="px-6 py-4"><?php echo htmlspecialchars($item['Nombre_producto']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($item['Cantidad_disponible']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($item['Nombre_almacen']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($item['Ubicacion']); ?></td>
            <td class="px-6 py-4">
                <form method="POST" action="retirar_producto.php">
                    <input type="hidden" name="id_almacen" value="<?php echo htmlspecialchars($id_almacen); ?>">
                    <input type="hidden" name="producto" value="<?php echo htmlspecialchars($item['Nombre_producto']); ?>">
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                        Retirar
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                    </table>
                </div>
                <?php unset($_SESSION['inventario']); ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        let html5QrCode = null;

        function selectWarehouse(warehouseId) {
            document.getElementById('id_almacen').value = warehouseId;
            document.getElementById('reader').style.display = 'block';
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                onScanSuccess,
                onScanError
            );
        }

        function onScanSuccess(decodedText) {
            html5QrCode.stop();
            document.getElementById('qr_code').value = decodedText;
            document.getElementById('qrForm').submit();
        }

        function onScanError(error) {
            console.error(error);
        }
    </script>
</body>
</html>


