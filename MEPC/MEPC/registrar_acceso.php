<?php
session_start();
require_once 'conexion_php_sqlS.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    
    // Aquí puedes agregar la lógica para seleccionar el almacén
    // Por ahora, usaremos un ID de almacén fijo (1) como ejemplo
    $id_almacen = 1;
    
    $fecha_acceso = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO Accesos (ID_usuario, ID_almacen, Fecha_acceso) VALUES (?, ?, ?)";
    $params = array($id_usuario, $id_almacen, $fecha_acceso);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    echo "Acceso registrado correctamente para el usuario ID: " . $id_usuario;
} else {
    echo "Error: Solicitud inválida";
}
