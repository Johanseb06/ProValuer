<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/db_config.php';
include "../includes/init.php";

$id_perfil = $_POST['perfil'];
$competencia = $_POST['competencia'];

if (empty($id_perfil)) {
    // die("⚠️ Debes seleccionar un perfil.");
    $_SESSION['mensaje'] = "⚠️ Debes seleccionar un perfil.";
    header("Location: ../forms/profile.php");
    exit;
}

$stmt = $conn->prepare("SELECT nombre_perfil FROM perfiles WHERE id_perfil = ?");
$stmt->bind_param("i", $id_perfil);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc();

if (!$perfil) {
    // die("⚠️ Perfil no encontrado.");
    $_SESSION['mensaje'] = "⚠️ Perfil no encontrado.";
    header("Location: ../forms/profile.php");
    exit;
}

$_SESSION['formulario']['perfil'] = [
    'id_perfil' => $id_perfil,
    'nombre_perfil' => $perfil['nombre_perfil']
];

$_SESSION['formulario']['competencia'] = [
    'competencia' => $competencia,
];

header("Location: ../forms/program.php");
exit;
