<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../config/db_config.php";
include "../includes/init.php";
include "../db/form_queries.php";

if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Categorias';

if (!isset($_SESSION['formulario']['postulante']) || !isset($_SESSION['formulario']['perfil'])) {
    die("Faltan datos del postulante o perfil.");
}

if (!isset($_SESSION['formulario']['programa'])) {
    die("⚠️ No hay un programa seleccionado.");
}
$programa = $_SESSION['formulario']['programa'];


$postulante = $_SESSION['formulario']['postulante'];
$perfil = $_SESSION['formulario']['perfil'];
$id_perfil = $_SESSION['formulario']['perfil']['id_perfil'];
/*
if (isset($_SESSION['formulario']['experiencia'])) {
    $exp = $_SESSION['formulario']['experiencia'];
    echo "<h3>Puntaje total de experiencia: {$exp['total']}</h3>";
    echo "<ul>";
    foreach ($exp['subcategorias'] as $nombre => $puntos) {
        echo "<li>$nombre: $puntos puntos</li>";
    }
    echo "</ul>";
}
    */
$experto = getYesNoSubcategoriesByCategoryName($conn, 'Experto', $id_perfil);
$formacion = getYesNoSubcategoriesByCategoryName($conn, 'Formación Laboral', $id_perfil);
$educacion = getYesNoSubcategoriesByCategoryName($conn, 'Educación', $id_perfil);
?>
<?php include('../../includes/panel/header.php'); ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="list-group">
                    <div class="step">1. Postulante</div>
                    <div class="step">2. Perfil</div>
                    <div class="step">3. Programa</div>
                    <div class="step">4. Experiencia</div>
                    <div class="step active">5. Categorias</div>
                    <div class="step">6. Resumen</div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4>Seleccionar</h4>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 83%">83%</div>
                        </div>
                    </div>
                    <div class="card-body">
                    <div class="dropdown mb-3">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Ver datos
                            </button>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item selecciones">Nombre: <?php echo htmlspecialchars($postulante['nombre_postulante']); ?></li>
                                <li class="dropdown-item selecciones">Documento: <?php echo htmlspecialchars($postulante['documento']); ?></li>
                                <li class="dropdown-item selecciones">Perfil: <?php echo $perfil['nombre_perfil']; ?></li>
                                <li class="dropdown-item selecciones">Programa: <?php echo htmlspecialchars($programa['nombre_programa']); ?></li>
                            </ul>
                        </div>
                        <form action="summary.php" method="post">
                            <div class="mb-3">
                                <h5>Experto</h5>
                                <?php while ($row = $experto->fetch_assoc()): ?>
                                    <div class="form-check">
                                        <p><?php echo $row['nombre_subcategoria']; ?>:</p>
                                        <input type="radio" class="btn-check" id="experto_si" name="experto[<?php echo $row['id_subcategoria']; ?>]" value="1" required>
                                        <label class="btn btn-outline-success" for="experto_si">Sí</label>
    
                                        <input type="radio" class="btn-check" id="experto_no" name="experto[<?php echo $row['id_subcategoria']; ?>]" value="0">
                                        <label class="btn btn-outline-danger" for="experto_no">No</label>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <div class="mb-3">
                                <h5>Formación laboral</h5>
                                <?php while ($row = $formacion->fetch_assoc()): ?>
                                    <div class="form-check">
                                        <p><?php echo $row['nombre_subcategoria']; ?>:</p>
                                        <input type="radio" class="btn-check" id="formacion_si" name="formacion[<?php echo $row['id_subcategoria']; ?>]" value="1" required>
                                        <label class="btn btn-outline-success" for="formacion_si">Sí</label>
    
                                        <input type="radio" class="btn-check" id="formacion_no" name="formacion[<?php echo $row['id_subcategoria']; ?>]" value="0">
                                        <label class="btn btn-outline-danger" for="formacion_no">No</label>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="educacion" class="form-label">Educación:</label>
                                <select name="educacion" id="educacion" class="form-control" required>
                                    <option value="">-- Selecciona --</option>
                                    
                                    <?php while ($row = $educacion->fetch_assoc()): ?>
                                    <option value="<?php echo $row['id_subcategoria']; ?>"><?php echo $row['nombre_subcategoria']; ?></option>
                                <?php endwhile; ?>
                                </select>
                                <?php $conn->close(); ?>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="experience.php" class="btn btn-outline-secondary">← Anterior</a>
                                <button type="submit" class="btn botones">Siguiente →</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 

<?php include('../../includes/panel/footer.php'); ?>