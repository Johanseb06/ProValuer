<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/db_config.php';
include '../db/form_queries.php';
include '../includes/init.php';

if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Resumen';

if (!isset($_SESSION['formulario']['postulante']) || !isset($_SESSION['formulario']['perfil'])) {
    // die("Faltan datos del postulante o perfil.");
    $_SESSION['mensaje'] = "⚠️ Faltan datos del postulante o perfil.";
    exit;
}
if (!isset($_SESSION['formulario']['programa'])) {
    // die("⚠️ No hay un programa seleccionado.");
    $_SESSION['mensaje'] = "⚠️ No hay un programa seleccionado.";
    exit;
}

$programa = $_SESSION['formulario']['programa'];
$postulante = $_SESSION['formulario']['postulante'];
$perfil = $_SESSION['formulario']['perfil'];
$id_perfil = $_SESSION['formulario']['perfil']['id_perfil'];
$puntos_experiencia = $_SESSION['formulario']['experiencia']['total'];

$_SESSION['formulario']['experto'] = $_POST['experto'] ?? [];
$_SESSION['formulario']['formacion_laboral'] = $_POST['formacion'] ?? [];

$puntosCategorias = [];

$puntosCategorias = obtenerPuntajesSubcategorias($conn, ['Experto', 'Formación Laboral', 'Educación'], $id_perfil);

$educacion_id = $_POST['educacion'] ?? null;
$_SESSION['formulario']['educacion']['id_subcategoria'] = $educacion_id;

$puntaje_educacion = 0;

?>
<?php include('../../includes/panel/header.php'); ?>

<div class='container mt-5'>
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <div class="step">1. Postulante</div>
                <div class="step">2. Perfil</div>
                <div class="step">3. Programa</div>
                <div class="step">4. Experiencia</div>
                <div class="step">5. Categorias</div>
                <div class="step active">6. Resumen</div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4>Resumen de los puntajes</h4>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%">100%</div>
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
                    <table class='table table-bordered table-striped'>
                        <thead class='table-success'>
                            <tr>
                                <th>Categoría</th>
                                <th>Detalle</th>
                                <th>Puntaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Experiencia</td>
                                <td>Subtotal por periodos trabajados</td>
                                <td><?= $puntos_experiencia ?> pts</td>
                            </tr>
                            <?php
                            $puntaje_experto = 0;
                            foreach ($_SESSION['formulario']['experto'] as $id_subcat => $valor) {
                                if (!isset($puntosCategorias[$id_subcat])) continue;
                                $marcado = $valor == 1 ? "Sí" : "No";
                                $puntaje = $valor == 1 ? $puntosCategorias[$id_subcat]['puntaje'] : 0;
                                $puntaje_experto += $puntaje;
                            ?>
                                <tr>
                                    <td><?= $puntosCategorias[$id_subcat]['nombre'] ?></td>
                                    <td>Marcado como '<?= $marcado ?>'</td>
                                    <td><?= $puntaje ?> pts</td>
                                </tr>
                            <?php }
                            $puntaje_formacion = 0;
                            foreach ($_SESSION['formulario']['formacion_laboral'] as $id_subcat => $valor) {
                                if (!isset($puntosCategorias[$id_subcat])) continue;
                                $marcado = $valor == 1 ? "Sí" : "No";
                                $puntaje = $valor == 1 ? $puntosCategorias[$id_subcat]['puntaje'] : 0;
                                $puntaje_formacion += $puntaje;
                            ?>
                                <tr>
                                    <td><?= $puntosCategorias[$id_subcat]['nombre'] ?></td>
                                    <td>Marcado como '<?= $marcado ?>'</td>
                                    <td><?= $puntaje ?> pts</td>
                                </tr>
                            <?php }  

                            if ($educacion_id && isset($puntosCategorias[$educacion_id])) {
                                $puntaje_educacion = $puntosCategorias[$educacion_id]['puntaje'];
                            ?>
                                <tr>
                                    <td>Educación</td>
                                    <td><?= $puntosCategorias[$educacion_id]['nombre'] ?></td>
                                    <td><?= $puntaje_educacion ?> pts</td>
                                </tr>
                            <?php }
                            $total_final = $puntos_experiencia + $puntaje_experto + $puntaje_formacion + $puntaje_educacion;

                            $_SESSION['puntaje']['total'] = $total_final;
                            $_SESSION['formulario']['experto']['total'] = $puntaje_experto;
                            $_SESSION['formulario']['formacion_laboral']['total'] = $puntaje_formacion;
                            $_SESSION['formulario']['educacion']['total'] = $puntaje_educacion;
                            ?>
                            <tr class='table-success fw-bold'>
                                <td colspan='2'>Total general</td>
                                <td><?= round($total_final, 2) ?> pts</td>
                            </tr>
                        </tbody>
                    </table>
                    <form action="../controllers/save_form.php" method="post">
                        <h5>Cumple:</h5>
                        <div class="form-check mb-3">
                            <input type="radio" class="btn-check" id="cumple_si" name="cumple" value="1" required>
                            <label class="btn btn-outline-success" for="cumple_si">Sí</label>

                            <input type="radio" class="btn-check" id="cumple_no" name="cumple" value="0">
                            <label class="btn btn-outline-danger" for="cumple_no">No</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="observaciones" placeholder="Deja un comentario aquí" id="floatingTextarea"></textarea>
                            <label for="floatingTextarea">Observaciones: </label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="form.php" class="btn btn-outline-secondary">← Anterior</a>
                            <button type="submit" class="btn botones">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/panel/footer.php'); ?>