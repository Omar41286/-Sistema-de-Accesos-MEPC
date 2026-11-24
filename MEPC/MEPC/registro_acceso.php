<?php
include 'conexion_php_sqlS.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $id_material = $_POST['id_material'];
    $cantidad = $_POST['cantidad'];

    // Verificar cantidad disponible antes de proceder
    $sql_check = "SELECT Cantidad_disponible FROM Inventario WHERE ID_producto = ?";
    $stmt_check = sqlsrv_query($conn, $sql_check, [$id_material]);

    if ($stmt_check === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $inventario = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);
    $cantidad_disponible = $inventario['Cantidad_disponible'];

    if ($cantidad > $cantidad_disponible) {
        echo "Error: La cantidad solicitada excede la cantidad disponible en el inventario.";
    } else {
        // Registrar acceso
        $sql = "INSERT INTO Acceso (ID_usuario, ID_material, cantidad_solicitada, fecha) VALUES (?, ?, ?, GETDATE())";
        $params = [$id_usuario, $id_material, $cantidad];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Actualizar inventario
        $sql_update = "UPDATE Inventario SET Cantidad_disponible = Cantidad_disponible - ? WHERE ID_producto = ?";
        $params_update = [$cantidad, $id_material];
        $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

        if ($stmt_update === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "Acceso registrado y inventario actualizado.";
    }

    // Libera los recursos
    sqlsrv_free_stmt($stmt_check);
    sqlsrv_free_stmt($stmt);
    if (isset($stmt_update)) {
        sqlsrv_free_stmt($stmt_update);
    }
}
?>

