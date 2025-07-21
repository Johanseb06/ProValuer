<?php
include '../../config/db_config.php';

$documento = $_GET['documento'] ?? '';

if (strlen($documento) < 2 ) {
    echo json_encode([]);
    exit;
}

$like = "$documento%";
$stmt = $conn->prepare("SELECT id_postulante, nombre_postulante, documento FROM postulantes WHERE documento LIKE ?");
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

$postulantes = [];
while ($row = $result->fetch_assoc()) {
    $postulantes[] = $row;
}

echo json_encode($postulantes);
