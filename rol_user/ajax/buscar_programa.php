<?php
include '../../config/db_config.php';

$nombre = $_GET['nombre'] ?? '';

if (strlen($nombre) < 2) {
    echo json_encode([]);
    exit;
}

$like = "%$nombre%";
$stmt = $conn->prepare("SELECT id_programa, nombre_programa, fecha_inicial, fecha_final FROM programas WHERE nombre_programa LIKE ?");
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

$programas = [];
while ($row = $result->fetch_assoc()) {
    $programas[] = $row;
}

echo json_encode($programas);
