<?php
include 'conexion_php_sqlS.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Acceso</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #0A192F; /* Color de fondo */
            color: #FFFFFF; /* Color de texto para contraste */
        }
        .table {
            background-color: #FFFFFF; /* Fondo blanco para la tabla */
            color: #000000; /* Color negro para el texto de la tabla */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Registros de Acceso</h2>

        <?php
        // Consulta para obtener los registros de acceso junto con nombres de usuario y almacén
        $sql = "SELECT a.ID_acceso, a.ID_usuario, u.Nombre AS Nombre_usuario, a.ID_almacen, al.Nombre AS Nombre_almacen, a.Fecha, a.Hora
                FROM Acceso a
                JOIN Usuario u ON a.ID_usuario = u.ID_usuario
                JOIN Almacen al ON a.ID_almacen = al.ID_almacen";
        $stmt = sqlsrv_query($conn, $sql);

        // Verifica si la consulta fue exitosa
        if ($stmt === false) {
            echo "<div class='alert alert-danger' role='alert'>Error al cargar los registros de acceso.</div>";
            die(print_r(sqlsrv_errors(), true));
        }
        ?>

<div class="container">
        <div class="d-flex justify-content-between mb-4">

        <a href="javascript:history.back()" class="btn btn-danger btn-back">
         <i class="bi bi-arrow-left-circle"></i> Volver
         </a>
        </div>

        <!-- Mostrar Registros de Acceso -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID Acceso</th>
                    <th>ID Usuario</th>
                    <th>Usuario</th>
                    <th>ID Almacén</th>
                    <th>Almacén</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Recorre los resultados y muestra los datos
                while ($acceso = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    // Convierte los valores DateTime a cadenas
                    $fecha = $acceso['Fecha'] ? $acceso['Fecha']->format('Y-m-d') : '';
                    $hora = $acceso['Hora'] ? $acceso['Hora']->format('H:i:s') : '';

                    echo "<tr>
                            <td>{$acceso['ID_acceso']}</td>
                            <td>{$acceso['ID_usuario']}</td>
                            <td>{$acceso['Nombre_usuario']}</td>
                            <td>{$acceso['ID_almacen']}</td>
                            <td>{$acceso['Nombre_almacen']}</td>
                            <td>{$fecha}</td>
                            <td>{$hora}</td>
                          </tr>";
                }

                // Libera los recursos de la consultas
                sqlsrv_free_stmt($stmt);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
