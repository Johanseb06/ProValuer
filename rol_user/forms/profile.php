<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db/profile_queries.php';
include '../../config/db_config.php';
include '../includes/init.php';

if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Perfil';

if (!isset($_SESSION['formulario']['postulante'])) {

    $_SESSION['mensaje'] = "⚠️ No hay un postulante seleccionado.";
    exit;
}
$postulante = $_SESSION['formulario']['postulante'];

$result = getProfile($conn);
?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <div class="step">1. Postulante</div>
                <div class="step active">2. Perfil</div>
                <div class="step">3. Programa</div>
                <div class="step">4. Experiencia</div>
                <div class="step">5. Categorias</div>
                <div class="step">6. Resumen</div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4>Seleccionar un perfil</h4>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 33%">33%</div>
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
                        </ul>
                    </div>

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Oh no!</strong> <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>
                    
                    <form method="post" action="../controllers/save_profile.php">
                        <div class="mb-3">
                            <label for="profile" class="form-label">Selecciona un perfil:</label>
                            <select name="perfil" id="profile" class="form-control" required>
                                <option value="">-- Selecciona --</option>
                                
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['id_perfil']; ?>"><?php echo $row['nombre_perfil']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <?php $conn->close(); ?>
                            </div>
                        <div class="mb-3">
                            <label for="competencia" class="form-label">Competencia</label>
                            <input type="text" class="form-control" id="competencia" name="competencia">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="postulant.php" class="btn btn-outline-secondary">← Anterior</a>
                            <button type="submit" class="btn botones">Siguiente →</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/panel/footer.php'); ?>