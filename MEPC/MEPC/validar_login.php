<?php
session_start(); // Inicia la sesión

include 'conexion_php_sqlS.php'; // Asegúrate de que este archivo contiene la conexión sqlsrv

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $contrasena = $_POST['contrasena'];

    if (empty($id_usuario) || empty($contrasena)) {
        $mensaje = "ID de usuario o contraseña incorrectos (datos vacíos).";
    } else {
        $sql = "SELECT * FROM Usuario WHERE ID_usuario = ?";
        $params = array($id_usuario);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            $mensaje = "Error en la consulta: " . print_r(sqlsrv_errors(), true);
        } else {
            $usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            
            if (!$usuario) {
                $mensaje = "ID de usuario o contraseña incorrectos (usuario inexistente).";
            } elseif (isset($usuario['Contraseña'])) {
                // Verificar si la contraseña es un hash (esto solo funciona si las contraseñas anteriores no están en hash)
                if (password_verify($contrasena, $usuario['Contraseña'])) {
                    // Contraseña correcta y ya cifrada
                    $_SESSION['id_usuario'] = $usuario['ID_usuario'];
                    $_SESSION['nombre_usuario'] = $usuario['Nombre'];
                    $_SESSION['rol_usuario'] = $usuario['ID_rol'];

                    // Redirigir según el rol del usuario
                    if ($usuario['ID_rol'] == 2) {
                        header("Location: generar_qr.php?id_usuario=" . $usuario['ID_usuario']);
                        exit();
                    } elseif ($usuario['ID_rol'] == 1) {
                        header("Location: panel_admin.php");
                        exit();
                    }
                } else {
                    // Si la contraseña no es un hash, la comparamos directamente (esto solo pasa para las contraseñas no hasheadas)
                    if ($usuario['Contraseña'] === $contrasena) {
                        // La contraseña no estaba cifrada, la actualizamos en la base de datos con un hash
                        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

                        // Actualizar la contraseña en la base de datos
                        $sql_update = "UPDATE Usuario SET Contraseña = ? WHERE ID_usuario = ?";
                        $params_update = array($contrasena_hash, $id_usuario);
                        $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

                        if ($stmt_update === false) {
                            $mensaje = "Error al actualizar la contraseña: " . print_r(sqlsrv_errors(), true);
                        } else {
                            // Guardar información del usuario en la sesión
                            $_SESSION['id_usuario'] = $usuario['ID_usuario'];
                            $_SESSION['nombre_usuario'] = $usuario['Nombre'];
                            $_SESSION['rol_usuario'] = $usuario['ID_rol'];

                            // Redirigir según el rol del usuario
                            if ($usuario['ID_rol'] == 2) {
                                header("Location: generar_qr.php?id_usuario=" . $usuario['ID_usuario']);
                                exit();
                            } elseif ($usuario['ID_rol'] == 1) {
                                header("Location: panel_admin.php");
                                exit();
                            }
                        }
                    } else {
                        $mensaje = "Contraseña incorrecta.";
                    }
                }
            } else {
                $mensaje = "ID de usuario o contraseña incorrectos (contraseña no encontrada).";
            }
            sqlsrv_free_stmt($stmt);
        }
    }
}

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEPC - Sistema de Almacén</title>
    <style>
        :root {
            --mepc-red: #E85A4F;
            --mepc-navy: #0A192F;
            --mepc-light: #f8f9fa;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--mepc-navy);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(-45deg, #0A192F, #1A2942, #2A3955, #3A4968);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .logo {
            display: block;
            margin: 0 auto 2rem;
            max-width: 200px;
            height: auto;
        }
        h1 {
            color: var(--mepc-navy);
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--mepc-navy);
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--mepc-red);
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--mepc-red);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #d04940;
            transform: translateY(-2px);
        }
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--mepc-navy);
            font-size: 0.9rem;
        }
        .error-message {
            color: var(--mepc-red);
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/MEPC-p8blHEpOjVDEGO1e1ZPx1nae2RpJq6.png" alt="MEPC Logo" class="logo">
        <h1>Acceso al Sistema</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="error-message"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="id_usuario">ID Usuario</label>
                <input type="text" id="id_usuario" name="id_usuario" required placeholder="Ingrese su ID">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required placeholder="Ingrese su contraseña">
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <div class="footer">
            MEPC Engineering Mexicana S.A. DE C.V.
        </div>
    </div>
</body>
</html>