<?php
session_start(); // Inicia la sesión

// Incluye el archivo de conexión
require_once 'conexion_php_sqlS.php';

if (!extension_loaded('sqlsrv')) {
    die('La extensión sqlsrv no está cargada en PHP.');
}

// Verifica si la conexión se estableció correctamente
if (!isset($conn) || $conn === false) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}

require 'phpqrcode/qrlib.php';

header('Content-Type: text/html; charset=utf-8');

function generarCodigoQR($id_usuario, $redirigir = false) {

    // Guardamos directamente el ID del usuario dentro del QR
    $datosQR = "ID_usuario:" . $id_usuario; // Aquí puedes usar el ID de forma directa

    
    $rutaQR = 'qrcodes/' . md5($datosQR) . '.png';

    // Verificar si el archivo ya existe
    if (file_exists($rutaQR)) {
        if ($redirigir) {
            header("Location: Panel_usuario.php?id_usuario=" . urlencode($id_usuario));
            exit();
        }
    } else {
        if (!is_dir('qrcodes')) { 
            mkdir('qrcodes', 0755, true); 
        }
        QRcode::png($datosQR, $rutaQR);
    }

    return $rutaQR;
}

// Ejemplo de uso 
$rutaQR = generarCodigoQR(123); 
echo "Código QR generado en: " . $rutaQR;

if (isset($_GET['id_usuario']) && ctype_digit($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    $sql = "SELECT * FROM Usuario WHERE ID_usuario = ?";
    $params = array($id_usuario);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
    }

 
    // Asegúrate de que $stmt y $id_usuario están definidos previamente

if (sqlsrv_has_rows($stmt)) {
    $usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Generar el código QR
    $rutaQR = generarCodigoQR($id_usuario, true);

    if (file_exists($rutaQR)) {
        // Si el archivo QR existe, muestra el código QR en una página HTML

        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Código QR para Usuario</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #f4f4f9;
                    color: #333;
                    text-align: center;
                }
                h2 {
                    color: #4a90e2;
                }
                img {
                    width: 250px; /* Ajuste de tamaño para el código QR */
                    height: 250px;
                    margin: 20px 0;
                    border: 2px solid #ccc;
                    padding: 10px;
                    background: #fff;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                p {
                    font-size: 1.1em;
                    color: #555;
                }
                .btn {
                    background-color: #4CAF50; /* Verde */
                    color: white;
                    padding: 10px 20px;
                    font-size: 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    text-decoration: none;
                }
                .btn:hover {
                    background-color: #45a049;
                }
            </style>
            <script>
        function recargarPagina() {
            // Recarga la página
            location.reload();
        }
    </script>
        </head>
        <body>
            <h2>Tu Código QR</h2>
            <img src='" . htmlspecialchars($rutaQR) . "' alt='Código QR'/>
            <p>Guarda este código para acceder al almacén deseado.</p>

           <!-- Botón que recarga la página -->
           <button class='btn' onclick='recargarPagina()'>Pasar al Panel de Usuario</button>
        </body>
        </html>";

    } else {
        // Si el archivo del QR no existe, mostrar un mensaje de error
        echo "Error al generar el código QR.";
    }
    }else {
        echo "Usuario no encontrado.";
    }
    } else {
    echo "ID de usuario no válido.";
}

if (isset($stmt) && $stmt !== false) {
    sqlsrv_free_stmt($stmt);
}

if (isset($conn) && $conn !== false) {
    sqlsrv_close($conn);
}

?>
