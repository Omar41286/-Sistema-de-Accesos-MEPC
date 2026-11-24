<?php
$serverName = 'localhost'; // Cambia por tu host
$database = 'SafeEntry';   // Cambia por el nombre de tu base de datos
$uid = 'Almacenes';   // Cambia por tu usuario de SQL Server
$pwd = '12345'; // Cambia por tu contraseña de SQL Server

// Configuración de la conexión
$connectionInfo = array(
    "Database" => $database,
    "UID" => $uid,
    "PWD" => $pwd,
    "CharacterSet" => "UTF-8"
);

// Intenta establecer la conexión
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die("Error de conexión: " . print_r(sqlsrv_errors(), true));
}

// Si la conexión es exitosa, no es necesario hacer nada más aquí.
// La variable $conn ahora contiene la conexión activa.
?>