<?php
// Cerrar sesión y redirigir al login
session_start();
session_destroy();
header("Location: Index.html");
exit();
?>