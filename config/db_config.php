<?php

$servername = "localhost"; // Cambia esto si tu servidor de base de datos no está en localhost
$username = "root"; // Tu nombre de usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$dbname = "valoracion"; // El nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>