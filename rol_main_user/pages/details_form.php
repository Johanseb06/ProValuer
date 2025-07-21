<?php session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol_usuario_fk"] != 2) {
    header("Location: /provaluer/index.php");
    exit();
}

include('../../includes/panel/header.php');
include '../../config/db_config.php';

$id = $_GET['id_formulario'] ?? null;
if (!$id) {
    echo "⚠️ Formulario no encontrado.";
    exit;
}

// Consulta principal
$sql = "SELECT f.*, p.nombre_programa, pe.nombre_perfil, po.nombre_postulante
        FROM formularios f
        JOIN programas p ON f.programa_formulario_fk = p.id_programa
        JOIN perfiles pe ON f.perfil_formulario_fk = pe.id_perfil
        JOIN postulantes po ON f.postulante_formulario_fk = po.id_postulante
        WHERE f.id_formulario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$formulario = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$formulario) {
    echo "❌ Formulario no encontrado.";
    exit;
}

// Consultar detalles con nombre de subcategoría
$detalles = [];
$sql_det = "SELECT d.tipo, s.nombre_subcategoria, d.valor, d.puntaje
            FROM formulario_detalles d
            JOIN subcategorias s ON d.subcategoria_detalle_fk = s.id_subcategoria
            WHERE d.formulario_detalle_fk = ?";
$stmt_det = $conn->prepare($sql_det);
$stmt_det->bind_param("i", $id);
$stmt_det->execute();
$res_det = $stmt_det->get_result();
while ($row = $res_det->fetch_assoc()) {
    $detalles[] = $row;
}
$stmt_det->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Formulario</title>
</head>
<body>
<div class="container mt-5">

    <div class="card shadow rounded-4">
        <div class="card-body">
            <h3 class="card-title mb-3">📝 Formulario de <strong><?= $formulario['nombre_postulante'] ?></strong></h3>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Programa:</strong> <?= $formulario['nombre_programa'] ?></li>
                <li class="list-group-item"><strong>Perfil:</strong> <?= $formulario['nombre_perfil'] ?></li>
                <li class="list-group-item"><strong>Puntaje total:</strong> <?= $formulario['puntaje_total'] ?></li>
                <li class="list-group-item"><strong>Observaciones:</strong> <?= $formulario['observaciones'] ?: 'Ninguna' ?></li>
            </ul>

            <h5 class="mb-3">📋 Detalles por subcategoría</h5>
            <table class="table table-striped table-bordered rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo</th>
                        <th>Subcategoría</th>
                        <th>Valor</th>
                        <th>Puntaje</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($detalles as $detalle): ?>
                    <tr>
                        <td><?= ucfirst($detalle['tipo']) ?></td>
                        <td><?= $detalle['nombre_subcategoria'] ?></td>
                        <td><?= $detalle['valor'] ?: '-' ?></td>
                        <td><?= $detalle['puntaje'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <a href="program.php" class="btn botones mt-3">← Volver</a>
        </div>
    </div>

</div>
</body>
</html>
