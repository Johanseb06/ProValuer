<?php
session_start();
include("../config/db_config.php");

// usuario responsable
mysqli_query($conn, "SET @usuario_actual := '".mysqli_real_escape_string($conn, $_SESSION['nombre_usuario'])."'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id_usuario"];
    $nombre = $_POST["nombre_usuario"];
    $numero = $_POST["documento"];
    $correo = $_POST["correo"];
    $rol = $_POST["role"];

    $sql = "UPDATE usuarios SET nombre_usuario='$nombre', documento=$numero, correo='$correo', rol_usuario_fk=$rol WHERE id_usuario=$id";

    if ($conn->query($sql)) {
        header("Location: ../rol_admin/pages/user_manager.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
