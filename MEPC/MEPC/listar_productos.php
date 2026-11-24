<?php
require_once 'conexion_php_sqlS.php';

// Eliminar Producto
if (isset($_POST['eliminar_producto'])) {
    $id_producto = $_POST['id_producto'];

    // Elimina el producto con el ID correspondiente
    $sql = "DELETE FROM Producto WHERE ID_producto = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id_producto]);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<div class='alert alert-success text-center'>Producto eliminado con éxito.</div>";
    }

    // Libera los recursos de la consulta
    sqlsrv_free_stmt($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
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
        .table thead th {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
        }
        .table tbody td {
            text-align: center;
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
            <h2 class="text-center">Lista de Productos</h2>
            <a href="javascript:history.back()" class="btn btn-danger btn-back">
               <i class="bi bi-arrow-left-circle"></i> Volver
            </a>

        </div>

        <!-- Mostrar Productos -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Leer Productos
                $sql = "SELECT * FROM Producto";
                $stmt = sqlsrv_query($conn, $sql);

                // Verifica si la consulta fue exitosa
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                // Recorre los resultados y muestra los datos
                while ($producto = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$producto['ID_producto']}</td>
                            <td>{$producto['Nombre_producto']}</td>
                            <td>{$producto['Descripcion']}</td>
                            <td>{$producto['Precio']}</td>
                            <td>
                                <form method='POST' action='listar_productos.php' style='display:inline-block'>
                                    <input type='hidden' name='id_producto' value='{$producto['ID_producto']}'>
                                    <button type='submit' name='eliminar_producto' class='btn btn-danger btn-sm'>
                                        <i class='bi bi-trash'></i> Eliminar
                                    </button>
                                </form>
                            </td>
                          </tr>";
                }

                // Libera los recursos de la consulta
                sqlsrv_free_stmt($stmt);
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
