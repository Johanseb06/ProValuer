<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db/experience_queries.php';
include "../../config/db_config.php";
include '../includes/init.php';

if (!isset($_SESSION['formulario']['postulante']) || !isset($_SESSION['formulario']['perfil'])) {
    die("Faltan datos del postulante o perfil.");
}

$postulante = $_SESSION['formulario']['postulante']['id_postulante'];
$id_perfil = $_SESSION['formulario']['perfil']['id_perfil'];
$resultado_experiencia = $_SESSION['formulario']['experiencia']['subcategorias'] ?? [];

$ponderacion = getSubcategoriesByCategoryName($conn, 'Experiencia', $id_perfil);

if ($resultado_experiencia !== null) {
    foreach ($resultado_experiencia as $id_subcategoria => $info) {
        $nombre = $info['nombre'];
        $puntaje_total = $info['puntaje_total'];
        $periodos = $info['periodos'];
        $row = $ponderacion->fetch_assoc();
        $ponderacion_max = $row['ponderacion_categoria'];
        if ($puntaje_total > $ponderacion_max) {
            $puntaje_total = $ponderacion_max;
        }
        

        // Insertar en experiencia_resumen
        $stmt_resumen = $conn->prepare("INSERT INTO experiencias_resumen (postulante_experiencia_fk, subcategoria_experiencia_fk, puntaje_total) VALUES (?, ?, ?)");
        $stmt_resumen->bind_param("iid", $postulante, $id_subcategoria, $puntaje_total);
        $stmt_resumen->execute();
        $id_resumen = $stmt_resumen->insert_id;
        $stmt_resumen->close();

        // Insertar los periodos asociados
        $stmt_periodo = $conn->prepare("INSERT INTO experiencias (resumen_experiencias_fk, fecha_inicio, fecha_fin, dias, puntaje) VALUES (?, ?, ?, ?, ?)");

        foreach ($periodos as $p) {
            $fecha_inicio = $p['inicio_str'];
            $fecha_fin = $p['fin_str'];
            $dias = $p['dias'];
            $puntaje = $p['puntaje'];

            if ($puntaje > $ponderacion_max) {
                $puntaje = $ponderacion_max;
            }

            $stmt_periodo->bind_param("issid", $id_resumen, $fecha_inicio, $fecha_fin, $dias, $puntaje);
            $stmt_periodo->execute();
        }

        $stmt_periodo->close();
    }

    $conn->close();

    header("Location: ../forms/form.php");
    exit;
} else {
    echo "No hay datos para guardar. ¿Ya pasaste por la página de cálculo?";
}
