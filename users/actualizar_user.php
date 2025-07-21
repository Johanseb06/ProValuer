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


    $sql = "UPDATE usuarios SET nombre_usuario='$nombre', documento=$numero, correo='$correo' WHERE id_usuario=$id";

    // Redireccionar según el rol
    if ($conn->query($sql)) {

        $_SESSION['rol_usuario_fk'] = $user['rol_usuario_fk'];

        if ($user["rol_usuario_fk"] == 1) {
            header("Location: /provaluer/user_roles/pages/profile.php");
        } elseif ($user["rol_usuario_fk"] == 2) {
            header("Location: /provaluer/user_roles/pages/profile.php");
        } else {
            header("Location: /provaluer/user_roles/pages/profile.php");
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
