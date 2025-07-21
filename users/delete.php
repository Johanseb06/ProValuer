<?php
session_start();
include("../config/db_config.php");

// usuario responsable
mysqli_query($conn, "SET @usuario_actual := '".mysqli_real_escape_string($conn, $_SESSION['nombre_usuario'])."'");

$id = $_GET["id"];

var_dump($id);
$sql = "DELETE FROM usuarios WHERE id_usuario=$id";

if ($conn->query($sql)) {
    header("Location: ../rol_admin/pages/user_manager.php");
} else {
    echo "Error: " . $conn->error;
}
