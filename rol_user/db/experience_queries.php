<?php
include '../../config/db_config.php';

function getExperience($conn) {
    if (!isset($_SESSION['formulario']['perfil']['id_perfil'])) {
        die("No se ha definido el perfil.");
    }

    $experience = 'Experiencia';
    $perfil_id = $_SESSION['formulario']['perfil']['id_perfil'];

    // Consulta preparada para mayor seguridad
    $stmt = $conn->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ? AND perfil_categoria_fk = ?");
    $stmt->bind_param("si", $experience, $perfil_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id_categoria = $row['id_categoria'];

        $sql = "SELECT id_subcategoria, nombre_subcategoria FROM subcategorias WHERE categoria_subcategoria_fk = $id_categoria";
        return $conn->query($sql);
    } else {
        return false; // No se encontró la categoría
    }
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

function getSubcategoryScore($conn) {
    $sql = "SELECT id_subcategoria, ponderacion_subcategoria_puntos FROM subcategorias";
    $result = $conn->query($sql);
    $puntajes = [];
    while ($row = $result->fetch_assoc()) {
        $puntajes[$row['id_subcategoria']] = floatval($row['ponderacion_subcategoria_puntos']);
    }
    return $puntajes;
}
