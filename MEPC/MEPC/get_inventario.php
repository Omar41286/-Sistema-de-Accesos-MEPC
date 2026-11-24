<?php
include 'conexion_php_sqlS.php';

header('Content-Type: application/json');

if (isset($_GET['warehouseId'])) {
    $warehouseId = $_GET['warehouseId'];
    
    $sql = "SELECT p.Nombre_producto, i.Cantidad_disponible, a.Nombre AS Nombre_almacen, a.Ubicacion
            FROM Inventario i
            JOIN Producto p ON i.ID_producto = p.ID_producto
            JOIN Almacen a ON i.ID_almacen = a.ID_almacen
            WHERE i.ID_almacen = ?";
    
    $params = array($warehouseId);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(json_encode(array('error' => 'Error en la consulta: ' . print_r(sqlsrv_errors(), true))));
    }

    $inventory = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $inventory[] = $row;
    }

    echo json_encode($inventory);
} else {
    echo json_encode(array('error' => 'No se proporcionó ID de almacén'));
}