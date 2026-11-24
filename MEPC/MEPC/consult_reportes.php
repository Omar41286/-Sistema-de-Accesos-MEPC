<?php
// Consultar el registro de acciones en el almacén
$sql = "SELECT l.Accion, l.Fecha, u.Nombre, i.Cantidad_disponible
        FROM Log_acciones l
        JOIN Usuario u ON l.ID_usuario = u.ID_usuario
        JOIN Inventario i ON l.ID_inventario = i.ID_inventario";
$stmt = sqlsrv_query($conn, $sql);

echo "<h2>Registro de Acciones</h2>";
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "Acción: " . $row['Accion'] . " - Fecha: " . $row['Fecha']->format('Y-m-d H:i:s') .
         " - Usuario: " . $row['Nombre'] . " - Cantidad Disponible: " . $row['Cantidad_disponible'] . "<br>";
}
?>