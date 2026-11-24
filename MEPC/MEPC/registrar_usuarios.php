<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de contraseña
    $id_rol = $_POST['id_rol']; // ID del rol seleccionado

    // Insertar en la tabla Usuario
    $sql = "INSERT INTO Usuario (Nombre, Correo, Contraseña, ID_rol) VALUES (?, ?, ?, ?)";
    $params = array($nombre, $correo, $contrasena, $id_rol);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    echo "Usuario registrado con éxito!";
}
?>

<form method="POST" action="">
    Nombre: <input type="text" name="nombre" required><br>
    Correo: <input type="email" name="correo" required><br>
    Contraseña: <input type="password" name="contrasena" required><br>
    Rol:
    <select name="id_rol">
        <option value="1">Administrador</option>
        <option value="2">Trabajador</option>
    </select><br>
    <input type="submit" value="Registrar">
</form>
