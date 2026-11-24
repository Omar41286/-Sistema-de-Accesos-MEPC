<?php
session_start();
require_once 'conexion_php_sqlS.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    // Redirigir al login si el usuario no ha iniciado sesión
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escanear QR</title>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        #preview {
            width: 100%;
            max-width: 500px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Escanear Código QR</h2>
        <video id="preview"></video>
        <div id="result" class="mt-3"></div>
    </div>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            document.getElementById('result').innerHTML = 'Escaneando...';
            // Extraer el ID de usuario del contenido escaneado
            let params = new URLSearchParams(new URL(content).search);
            let id_usuario = params.get('id_usuario');
            
            if (id_usuario) {
                // Enviar el ID de usuario al servidor
                fetch('registrar_acceso.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id_usuario=' + id_usuario
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('result').innerHTML = data;
                })
                .catch((error) => {
                    console.error('Error:', error);
                    document.getElementById('result').innerHTML = 'Error al procesar el código QR';
                });
            } else {
                document.getElementById('result').innerHTML = 'Código QR inválido';
            }
        });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No se encontraron cámaras');
                document.getElementById('result').innerHTML = 'No se encontraron cámaras';
            }
        }).catch(function (e) {
            console.error(e);
            document.getElementById('result').innerHTML = 'Error al acceder a la cámara';
        });
    </script>
</body>
</html>