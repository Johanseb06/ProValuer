<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../../config/db_config.php";
include "../includes/init.php";

if ($_POST['modo'] === 'nuevo') {
    $documento = trim($_POST['document']);
    $nombre = trim($_POST['name']);

    if (empty($documento) || empty($nombre)) {
        // die("⚠️ Todos los campos son obligatorios.");
        $_SESSION['mensaje'] = "⚠️ Todos los campos son obligatorios.";
        header("Location: ../forms/postulant.php");
        exit;
    }

    $verifica = $conn->prepare("SELECT id_postulante FROM postulantes WHERE documento = ?");
    $verifica->bind_param("i", $documento);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        // echo "⚠️ Ya existe un postulante con ese documento.";
        $_SESSION['mensaje'] = "⚠️ Ya existe un postulante con ese documento.";
        header("Location: ../forms/postulant.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO postulantes (documento, nombre_postulante) VALUES (?,?)");
    $stmt->bind_param("is", $documento, $nombre);

    if ($stmt->execute()) {
        $id_postulante = $stmt->insert_id;

        $_SESSION['formulario']['postulante'] = [
            'nuevo' => true,
            'id_postulante' => $id_postulante,
            'nombre_postulante' => $nombre,
            'documento' => $documento
        ];

        header("Location: ../forms/profile.php");
        exit;
    } else {
        // echo "❌ Error al insertar el postulante.";
        $_SESSION['mensaje'] = "❌ Error al insertar el postulante.";
        header("Location: ../forms/postulant.php");
        exit;
    }
}

if ($_POST['modo'] === 'existente') {
    $id_postulante = $_POST['id_postulante'];

    if (empty($id_postulante)) {
        // die("⚠️ No se recibió el ID del postulante existente.");
        $_SESSION['mensaje'] = "⚠️ No se recibió el ID del postulante existente.";
        header("Location: ../forms/postulant.php");
        exit;
    }

    // Traemos los datos del postulante para la sesión
    $stmt = $conn->prepare("SELECT id_postulante, nombre_postulante, documento FROM postulantes WHERE id_postulante = ?");
    $stmt->bind_param("i", $id_postulante);
    $stmt->execute();
    $result = $stmt->get_result();
    $postulante = $result->fetch_assoc();

    if (!$postulante) {
        // die("⚠️ El postulante no fue encontrado.");
        $_SESSION['mensaje'] = "⚠️ El postulante no fue encontrado..";
        header("Location: ../forms/postulant.php");
        exit;
    }

    // Guardamos en sesión
    $_SESSION['formulario']['postulante'] = [
        'nuevo' => false,
        'id_postulante' => $postulante['id_postulante'],
        'nombre_postulante' => $postulante['nombre_postulante'],
        'documento' => $postulante['documento']
    ];

    header("Location: ../forms/profile.php");
    exit;
}