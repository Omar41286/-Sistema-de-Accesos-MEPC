<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];
    $id_usuario = $_POST['id_usuario'];
    $id_inventario = $_POST['id_inventario'];
    $fecha = date("Y-m-d H:i:s");

    // Insertar en la tabla Log_acciones
    $sql = "INSERT INTO Log_acciones (Accion, Fecha, ID_usuario, ID_inventario) VALUES (?, ?, ?, ?)";
    $params = array($accion, $fecha, $id_usuario, $id_inventario);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    echo "Acción registrada exitosamente!";
}
?>

<form method="POST" action="">
    Acción: <input type="text" name="accion" required><br>
    ID Usuario: <input type="text" name="id_usuario" required><br>
    ID Inventario: <input type="text" name="id_inventario" required><br>
    <input type="submit" value="Registrar Acción">
</form>
