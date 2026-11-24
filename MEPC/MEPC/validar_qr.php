<?php
include 'conexion_php_sqlS.php';

if (isset($_POST['qr_code'])) {
    $qr_code = $_POST['qr_code'];
    
    // Extraer el ID del usuario desde el código QR
    parse_str($qr_code, $params);
    $id_usuario = $params['ID_usuario'];

    // Verificar el usuario y permitir la selección de materiales
    $sql = "SELECT * FROM Usuario WHERE ID_usuario = ?";
    $stmt = sqlsrv_query($conn, array($id_usuario));

    if ($stmt === false || sqlsrv_num_rows($stmt) == 0) {
        die("Código QR inválido.");
    }

    // Muestra el inventario
    echo "<h2>Seleccione Material del Inventario</h2>";
    // Mostrar selección de materiales
    // Luego, se usará el archivo `registro_acceso.php` para actualizar el inventario.
}
?>
