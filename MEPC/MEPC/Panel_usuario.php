<?php
include 'conexion_php_sqlS.php';

session_start(); // Inicia la sesión

// Verifica que se ha proporcionado un ID de usuario válido en la URL
if (!isset($_GET['id_usuario']) || !is_numeric($_GET['id_usuario'])) {
    die("ID de usuario no válido.");
}

// Convertir el ID de usuario a entero
$id_usuario = intval($_GET['id_usuario']); 

// Verifica si la conexión se estableció correctamente
if (!isset($conn) || $conn === false) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}

// Consulta para obtener información del usuario
$sql = "SELECT Nombre, Correo, ID_rol, imagen_perfil FROM Usuario WHERE ID_usuario = ?";
$params = array($id_usuario);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

$usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Determina el rol basado en el ID_rol
$rol = ($usuario['ID_rol'] == 1) ? 'Administrador' : 'Usuario';

// Manejo de carga de imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_perfil'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagen_perfil"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si se ha subido un archivo
    if ($_FILES["imagen_perfil"]["tmp_name"] !== '') {
        // Verificar si el archivo es una imagen real
        $check = getimagesize($_FILES["imagen_perfil"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }

        // Verificar el tamaño del archivo
        if ($_FILES["imagen_perfil"]["size"] > 500000) {
            echo "Lo siento, tu archivo es demasiado grande.";
            $uploadOk = 0;
        }

        // Permitir ciertos formatos de archivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG & GIF.";
            $uploadOk = 0;
        }

        // Subir el archivo
        if ($uploadOk == 1) {
            $new_file_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_file_name;
            if (move_uploaded_file($_FILES["imagen_perfil"]["tmp_name"], $target_file)) {
                // Actualizar la base de datos con la nueva imagen
                $sql_update = "UPDATE Usuario SET imagen_perfil = ? WHERE ID_usuario = ?";
                $params_update = array($target_file, $id_usuario);
                $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);
                if ($stmt_update === false) {
                    die("Error al actualizar la imagen de perfil: " . print_r(sqlsrv_errors(), true));
                }
                $usuario['imagen_perfil'] = $target_file;
            } else {
                echo "Lo siento, hubo un error subiendo tu archivo.";
            }
        }
    } else {
        echo "No se ha seleccionado ningún archivo.";
    }
}

// Cerramos la consulta y conexión
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - SafeEntry</title>
     <img
                alt="MEPC Logo"
                class="h-12 w-auto"
                src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/MEPC-p8blHEpOjVDEGO1e1ZPx1nae2RpJq6.png"
            />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0A192F] to-[#3A4968] p-8">
    <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden bg-white/95 p-8 shadow-xl rounded-lg">
            <div class="mb-8 flex items-center justify-between">
           
                <h1 class="text-3xl font-bold text-[#0A192F]">Panel de Usuario</h1>
                <a href="logout.php" class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                    <i class="bi bi-box-arrow-right mr-2"></i>
                    Cerrar Sesión
                </a>
            </div>

            <div class="mb-8 grid gap-8 md:grid-cols-3">
                <div class="flex flex-col items-center space-y-4">
                    <img
                        src="<?php echo htmlspecialchars($usuario['imagen_perfil'] ?? '/placeholder.svg'); ?>"
                        alt="Imagen de perfil"
                        class="h-40 w-40 rounded-full border-4 border-white object-cover shadow-lg"
                    />
                    <form action="" method="post" enctype="multipart/form-data">
                        <input
                            type="file"
                            name="imagen_perfil"
                            accept="image/*"
                            class="max-w-[200px] block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 file:text-violet-700
                                hover:file:bg-violet-100"
                        />
                        <button type="submit" class="mt-4 w-full max-w-[200px] rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                            Actualizar imagen
                        </button>
                    </form>
                </div>
                <div class="col-span-2 rounded-lg border p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold">Resumen del Usuario</h2>
                    <div class="space-y-2">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['Nombre']); ?></p>
                        <p><strong>ID de Usuario:</strong> <?php echo htmlspecialchars($id_usuario); ?></p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario['Correo']); ?></p>
                        <p><strong>Rol:</strong> <?php echo htmlspecialchars($rol); ?></p>
                    </div>
                </div>
            </div>

            <h2 class="mb-4 text-2xl font-semibold">Acciones</h2>
            <div class="grid gap-4 md:grid-cols-3">
                <a href="Lista_Producto_usuario.php" class="flex items-center justify-start gap-2 rounded-md bg-blue-600 px-4 py-6 text-lg text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                    <i class="bi bi-list-check text-2xl"></i>
                    Lista de Productos
                </a>
                <a href="inventario_almacenes.php" class="flex items-center justify-start gap-2 rounded-md bg-green-600 px-4 py-6 text-lg text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
                    <i class="bi bi-building text-2xl"></i>
                    Inventario de Almacenes
                </a>
                <a href="Vizualizar Qr.php?id_usuario=3" class="flex items-center justify-start gap-2 rounded-md bg-yellow-600 px-4 py-6 text-lg text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:ring-offset-2">
                    <i class="bi bi-list-check text-2xl"></i>
                    Ver mi QR
                </a>
            </div>
        </div>
    </div>
</body>
</html>