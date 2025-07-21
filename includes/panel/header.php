<?php
// session_start();
$rol = $_SESSION['rol_usuario_fk'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        ProValuer
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <!-- google material-symbols-rounded https://fonts.google.com/icons?icon.set=Material+Symbols&icon.style=Rounded&icon.size=24&icon.color=%231f1f1f -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- CSS Files -->
    <link href="/provaluer/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/provaluer/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/provaluer/assets/img/logo.png">
</head>

<body class="d-flex">

    <?php include('sidebar.php'); ?>

    <main class="flex-grow-1 p-4" style="margin-left: 280px">
    
    <?php include('navbar.php');?>