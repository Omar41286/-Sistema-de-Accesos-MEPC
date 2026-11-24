<?php
include 'conexion_php_sqlS.php';

// Eliminar Usuario
if (isset($_POST['eliminar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    // Consulta para eliminar el usuario con el ID correspondiente
    $sql = "DELETE FROM Usuario WHERE ID_usuario = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id_usuario]);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<div class='alert alert-success text-center'>Usuario eliminado correctamente.</div>";
    }

    // Libera los recursos de la consulta de eliminación
    sqlsrv_free_stmt($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
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
            <h2 class="text-center">Lista de Usuarios</h2>
            <a href="javascript:history.back()" class="btn btn-danger btn-back">
               <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
        </div>

        <!-- Mostrar Usuarios -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener los usuarios
                $sql = "SELECT u.ID_usuario, u.Nombre, u.Correo, r.Rol 
                        FROM Usuario u 
                        LEFT JOIN Rol r ON u.ID_rol = r.ID_rol";
                $stmt = sqlsrv_query($conn, $sql);

                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                // Recorre los resultados y muestra los datos
                while ($usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$usuario['ID_usuario']}</td>
                            <td>{$usuario['Nombre']}</td>
                            <td>{$usuario['Correo']}</td>
                            <td>{$usuario['Rol']}</td>
                            <td>
                                <form method='POST' action='listar_usuarios.php' style='display:inline-block'>
                                    <input type='hidden' name='id_usuario' value='{$usuario['ID_usuario']}'>
                                    <button type='submit' name='eliminar_usuario' class='btn btn-danger btn-sm'>
                                        <i class='bi bi-trash'></i> Eliminar
                                    </button>
                                </form>
                            </td>
                          </tr>";
                }

                // Libera los recursos de la consulta de selección
                sqlsrv_free_stmt($stmt);
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

