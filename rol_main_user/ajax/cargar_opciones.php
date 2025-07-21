<?php session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol_usuario_fk"] != 2) {
    header("Location: /provaluer/index.php");
    exit();
}
include '../../config/db_config.php';

$tabla = $_GET['tabla'] ?? '';
$respuesta = [];

switch ($tabla) {
    case 'programas':
        $sql = "SELECT id_programa AS id, nombre_programa AS nombre FROM programas";
        break;
    case 'perfiles':
        $sql = "SELECT id_perfil AS id, nombre_perfil AS nombre FROM perfiles";
        break;
    case 'postulantes':
        $sql = "SELECT id_postulante AS id, nombre_postulante AS nombre FROM postulantes";
        break;
    default:
        echo json_encode([]);
        exit;
}

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $respuesta[] = $row;
}

echo json_encode($respuesta);
$conn->close();
