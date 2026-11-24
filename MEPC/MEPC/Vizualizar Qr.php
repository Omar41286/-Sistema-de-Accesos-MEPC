<?php
// Incluye el archivo de conexión
require_once 'conexion_php_sqlS.php';

session_start();

if (!isset($conn) || $conn === false) {
    die("Error: No se pudo establecer la conexión a la base de datos.");
}


// Verifica si se proporciona el ID del usuario
if (isset($_GET['id_usuario']) && ctype_digit($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    // Genera la ruta del archivo QR basado en el ID del usuario
    $datosQR = "ID_usuario:" . $id_usuario;
    $rutaQR = 'qrcodes/' . md5($datosQR) . '.png';

    

    // Verifica si el archivo QR existe
    if (file_exists($rutaQR)) {
        // Muestra la imagen en una página HTML
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Código QR</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #f9f9f9;
                    color: #333;
                    text-align: center;
                }
                img {
                    width: 250px;
                    height: 250px;
                    margin: 20px 0;
                    border: 1px solid #ddd;
                    background: #fff;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                p {
                    font-size: 16px;
                    color: #555;
                }
            </style>
        </head>
        <body>
            <h2>Código QR del Usuario</h2>
            <img src='" . htmlspecialchars($rutaQR) . "' alt='Código QR'/>
            <p>Escanea este código para acceder al sistema.</p>
        </body>
        </html>";
    } else {
        // Si no existe el archivo QR
        echo "<p>Error: No se encontró el código QR para el usuario especificado.</p>";
    }
    } else {
    // Si no se proporciona un ID válido
    echo "<p>Error: ID de usuario no válido.</p>";
}
?>

