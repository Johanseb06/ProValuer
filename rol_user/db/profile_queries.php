<?php
include '../../config/db_config.php';

function getProfile($conn) {
    $sql = "SELECT id_perfil, nombre_perfil FROM perfiles";
    return $conn->query($sql);
}