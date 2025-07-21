<?php session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol_usuario_fk"] != 2) {
    header("Location: /provaluer/index.php");
    exit();
}
include '../../config/db_config.php';

$where = [];
$params = [];
$types = '';

if (!empty($_POST['programa'])) {
    $where[] = 'f.programa_formulario_fk = ?';
    $params[] = $_POST['programa'];
    $types .= 'i';
}
if (!empty($_POST['perfil'])) {
    $where[] = 'f.perfil_formulario_fk = ?';
    $params[] = $_POST['perfil'];
    $types .= 'i';
}
if (!empty($_POST['postulante'])) {
    $where[] = 'f.postulante_formulario_fk = ?';
    $params[] = $_POST['postulante'];
    $types .= 'i';
}

$sql = "SELECT f.id_formulario, p.nombre_programa, pf.nombre_perfil, po.nombre_postulante, f.puntaje_total
        FROM formularios f
        JOIN programas p ON f.programa_formulario_fk = p.id_programa
        JOIN perfiles pf ON f.perfil_formulario_fk = pf.id_perfil
        JOIN postulantes po ON f.postulante_formulario_fk = po.id_postulante";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<div class='table-responsive'>";
    echo "<table class='table table-hover table-bordered align-middle rounded shadow-sm'>
            <thead class='table-dark'>
                <tr>
                    <th>#</th>
                    <th>Programa</th>
                    <th>Perfil</th>
                    <th>Postulante</th>
                    <th>Puntaje</th>
                    <th class='text-center'>Acciones</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id_formulario']}</td>
                <td>{$row['nombre_programa']}</td>
                <td>{$row['nombre_perfil']}</td>
                <td>{$row['nombre_postulante']}</td>
                <td><span class='badge bg-secondary'>{$row['puntaje_total']}</span></td>
                <td class='text-center'>
                    <a href='../pages/details_form.php?id_formulario={$row['id_formulario']}' class='btn botones btn-sm'>
                        <i class='bi bi-eye'></i> Detalles
                    </a>
                </td>
            </tr>";
    }

    echo "</tbody></table>";
    echo "</div>";
} else {
    echo "<div class='alert alert-warning mt-3'>⚠️ No se encontraron formularios con esos filtros.</div>";
}


$stmt->close();
$conn->close();
