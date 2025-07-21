<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/db_config.php';
include "../includes/init.php";

if ($_POST['modo'] === 'nuevo') {
    // Recibimos datos
    $programa = trim($_POST['programa']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_final'];

    // Validación simple
    if (empty($programa) || empty($fecha_inicio) || empty($fecha_fin)) {
        // die("⚠️ Todos los campos son obligatorios.");
        $_SESSION['mensaje'] = "⚠️ Todos los campos son obligatorios.";
        header("Location: ../forms/program.php");
        exit;
    }
    // Verificamos si ya existe uno con el mismo nombre y fechas
    $verifica = $conn->prepare("SELECT id_programa FROM programas WHERE nombre_programa = ? AND fecha_inicial = ? AND fecha_final = ?");
    $verifica->bind_param("sss", $programa, $fecha_inicio, $fecha_fin);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        // echo "⚠️ Ya existe un programa con ese nombre y fechas.";
        $_SESSION['mensaje'] = "⚠️ Ya existe un programa con ese nombre y fechas.";
        header("Location: ../forms/program.php");
        exit;
    }

    // Insertar el nuevo programa
    $stmt = $conn->prepare("INSERT INTO programas (nombre_programa, fecha_inicial, fecha_final) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $programa, $fecha_inicio, $fecha_fin);
    
    if ($stmt->execute()) {
        $id_programa = $stmt->insert_id;  // Obtener el ID recién insertado

        $_SESSION['formulario']['programa'] = [
            'nuevo' => true,
            'id_programa' => $id_programa,
            'nombre_programa' => $programa
        ];

        header("Location: ../forms/experience.php");
        exit;
    } else {
        // echo "❌ Error al insertar el programa.";
        $_SESSION['mensaje'] = "❌ Error al insertar el programa.";
        header("Location: ../forms/program.php");
        exit;
    }
}

if ($_POST['modo'] === 'existente') {

    $id_programa = $_POST['id_programa'] ?? null;

    if (!$id_programa) {
        // die("❌ No se seleccionó ningún programa.");
        $_SESSION['mensaje'] = "❌ No se seleccionó ningún programa.";
        header("Location: ../forms/program.php");
        exit;
    }

    // Opcionalmente, podrías validar si existe en la base de datos
    $stmt = $conn->prepare("SELECT nombre_programa FROM programas WHERE id_programa = ?");
    $stmt->bind_param("i", $id_programa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['formulario']['programa'] = [
            'nuevo' => false,
            'id_programa' => $id_programa,
            'nombre_programa' => $row['nombre_programa']
        ];

        header("Location: ../forms/experience.php");
        exit;
    } else {
        // echo "❌ El programa seleccionado no existe.";
        $_SESSION['mensaje'] = "❌ El programa seleccionado no existe.";
        header("Location: ../forms/program.php");
        exit;
    }
}