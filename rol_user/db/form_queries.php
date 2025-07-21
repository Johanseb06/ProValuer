<?php
include '../../config/db_config.php';

function getYesNoSubcategoriesByCategoryName($conn, $nombreCategoria, $idPerfil) {
    $stmt = $conn->prepare("
        SELECT s.id_subcategoria, s.nombre_subcategoria 
        FROM subcategorias s
        JOIN categorias c ON s.categoria_subcategoria_fk = c.id_categoria
        WHERE c.nombre_categoria = ? AND c.perfil_categoria_fk = ?
    ");
    $stmt->bind_param("si", $nombreCategoria, $idPerfil);
    $stmt->execute();
    return $stmt->get_result();
}

function getSubcategoriesByCategoryName($conn, $nombreCategoria, $idPerfil) {
    $stmt = $conn->prepare("
        SELECT s.id_subcategoria, s.nombre_subcategoria, s.ponderacion_subcategoria_porcentaje, c.ponderacion_categoria  
        FROM subcategorias s
        JOIN categorias c ON s.categoria_subcategoria_fk = c.id_categoria
        WHERE c.nombre_categoria = ? AND c.perfil_categoria_fk = ?
    ");
    $stmt->bind_param("si", $nombreCategoria, $idPerfil);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerPuntajesSubcategorias($conn, $categorias, $id_perfil)
{
    $puntajes = [];
    foreach ($categorias as $nombreCategoria) {
        $resultado = getSubcategoriesByCategoryName($conn, $nombreCategoria, $id_perfil);
        while ($row = $resultado->fetch_assoc()) {
            $puntajes[$row['id_subcategoria']] = [
                'nombre' => $row['nombre_subcategoria'],
                'puntaje' => ($row['ponderacion_categoria'] * $row['ponderacion_subcategoria_porcentaje']) / 100,
            ];
        }
    }
    return $puntajes;
}