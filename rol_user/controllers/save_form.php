<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/db_config.php';
include "../includes/init.php";
include '../db/form_queries.php';

if (!isset($_SESSION['formulario'])) {
    die("No hay datos del formulario en la sesión.");
}

$_SESSION['formulario']['cumple'] = $_POST['cumple'] ?? 0;
$_SESSION['formulario']['observaciones'] = $_POST['observaciones'] ?? '';
$formulario = $_SESSION['formulario'];

$id_perfil = $_SESSION['formulario']['perfil']['id_perfil'];

// Variables principales
$programa_fk = $formulario['programa']['id_programa'] ?? null;
$postulante_fk = $formulario['postulante']['id_postulante'] ?? null;
$usuario_fk = $_SESSION['id_usuario'] ?? null;
$perfil_fk = $formulario['perfil']['id_perfil'] ?? null;

// Puntajes
$competencia = $formulario['competencia']['competencia'] ?? '';
$experto = $formulario['experto']['total'] ?? 0;
$formacion_laboral = $formulario['formacion_laboral']['total'] ?? 0;
$educacion = $formulario['educacion']['total'] ?? 0;
$experiencia = $formulario['experiencia']['total'] ?? 0;

// Otros
$cumple = $formulario['cumple'] ?? 0;
$observaciones = $formulario['observaciones'] ?? '';
$puntaje_total = $experto + $formacion_laboral + $educacion + $experiencia;

$puntosCategorias = obtenerPuntajesSubcategorias($conn, ['Experto', 'Formación Laboral', 'Educación'], $id_perfil);


// 1. Insertar en formulario
$stmt_form = $conn->prepare("INSERT INTO formularios (programa_formulario_fk, perfil_formulario_fk, postulante_formulario_fk, usuario_formulario_fk, experiencia, experto, formacion_laboral, educacion, competencia, cumple, observaciones, puntaje_total)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_form->bind_param("iiiidiiisisd", $programa_fk, $perfil_fk, $postulante_fk, $usuario_fk, $experiencia, $experto, $formacion_laboral, $educacion, $competencia, $cumple, $observaciones, $puntaje_total);
$stmt_form->execute();
$formulario_id = $stmt_form->insert_id;
$stmt_form->close();

// 2. Insertar detalles: experto
$stmt_det = $conn->prepare("INSERT INTO formulario_detalles (formulario_detalle_fk, subcategoria_detalle_fk, tipo, valor, puntaje) VALUES (?, ?, ?, ?, ?)");
foreach ($_SESSION['formulario']['experto'] as $id_subcat => $valor) {
    if ($id_subcat === 'total') continue;
    $tipo = 'experto';
    $texto = $valor == 1 ? 'Sí' : 'No';
    $puntaje = $valor == 1 ? $puntosCategorias[$id_subcat]['puntaje'] : 0;
    $stmt_det->bind_param("iissd", $formulario_id, $id_subcat, $tipo, $texto, $puntaje);
    $stmt_det->execute();
}

// 3. Insertar detalles: formacion_laboral
foreach ($_SESSION['formulario']['formacion_laboral'] as $id_subcat => $valor) {
    if ($id_subcat === 'total') continue;
    $tipo = 'formacion';
    $texto = $valor == 1 ? 'Sí' : 'No';
    $puntaje = $valor == 1 ? $puntosCategorias[$id_subcat]['puntaje'] : 0;
    $stmt_det->bind_param("iissd", $formulario_id, $id_subcat, $tipo, $texto, $puntaje);
    $stmt_det->execute();
}

// 4. Insertar educación
$educacion_id = $_SESSION['formulario']['educacion']['id_subcategoria'];
if (isset($puntosCategorias[$educacion_id])) {
    $tipo = 'educacion';
    $texto = $puntosCategorias[$educacion_id]['nombre'];
    $puntaje = $puntosCategorias[$educacion_id]['puntaje'];
    $stmt_det->bind_param("iissd", $formulario_id, $educacion_id, $tipo, $texto, $puntaje);
    $stmt_det->execute();
}
// 5. Insertar detalles: experiencia
if (isset($_SESSION['formulario']['experiencia']['subcategorias'])) {
    $tipo = 'experiencia';
    foreach ($_SESSION['formulario']['experiencia']['subcategorias'] as $id_subcat => $data) {
        $valor = ''; // Podés dejarlo vacío o poner un resumen como "3 periodos"
        $puntaje = $data['puntaje_total'];

        // Limitar al máximo según ponderación si es necesario
        if (isset($puntosCategorias[$id_subcat]) && $puntaje > $puntosCategorias[$id_subcat]['puntaje']) {
            $puntaje = $puntosCategorias[$id_subcat]['puntaje'];
        }

        $stmt_det->bind_param("iissd", $formulario_id, $id_subcat, $tipo, $valor, $puntaje);
        $stmt_det->execute();
    }
}
$stmt_det->close();
$_SESSION['guardado'] = "✅ Formulario guardado con éxito.";
header("Location: ../forms/postulant.php");
exit;
