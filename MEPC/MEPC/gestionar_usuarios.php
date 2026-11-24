<?php
include 'conexion_php_sqlS.php';

// Crear Usuario
if (isset($_POST['crear_usuario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Contraseña cifrada
    $id_rol = $_POST['id_rol'];

    // Inserta un nuevo usuario en la base de datos
    $sql = "INSERT INTO Usuario (Nombre, Correo, Contraseña, ID_rol) VALUES (?, ?, ?, ?)";
    $params = [$nombre, $correo, $contrasena, $id_rol];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "<div class='alert alert-success text-center'>Usuario creado correctamente.</div>";
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
    <title>Gestionar Usuarios</title>
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
        .form-group label {
            font-weight: 500;
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
            <h2 class="text-center">Gestión de Usuarios</h2>
            <a href="javascript:history.back()" class="btn btn-danger btn-back">
               <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
        </div>

        <!-- Formulario para Crear Usuarios -->
        <form method="POST" action="gestionar_usuarios.php">
            <div class="form-group mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group mb-3">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="form-group mb-3">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <div class="form-group mb-3">
                <label for="id_rol">Rol:</label>
                <select class="form-control" id="id_rol" name="id_rol" required>
                    <option value="1">Administrador</option>
                    <option value="2">Trabajador</option>
                </select>
            </div>
            <button type="submit" name="crear_usuario" class="btn btn-primary w-100">Crear Usuario</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
