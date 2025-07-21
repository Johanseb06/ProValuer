<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db/experience_queries.php';
include "../../config/db_config.php";
include '../includes/init.php';

if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Experiencia';

if (!isset($_SESSION['formulario']['postulante'])) {
    die("⚠️ No hay un postulante seleccionado.");
}
$postulante = $_SESSION['formulario']['postulante'] ?? null;

if (!isset($_SESSION['formulario']['perfil'])) {
    die("⚠️ No hay un perfil seleccionado.");
}
$perfil = $_SESSION['formulario']['perfil'];

if (!isset($_SESSION['formulario']['programa'])) {
    die("⚠️ No hay un programa seleccionado.");
}
$programa = $_SESSION['formulario']['programa'];

$subcategorias = getExperience($conn);

?>
<?php include('../../includes/panel/header.php'); ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="list-group">
                    <div class="step">1. Postulante</div>
                    <div class="step">2. Perfil</div>
                    <div class="step">3. Programa</div>
                    <div class="step active">4. Experiencia</div>
                    <div class="step">5. Categorias</div>
                    <div class="step">6. Resumen</div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4>Calcular la experiencia</h4>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 66%">66%</div>
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
                        <form action="calculate_experience.php" method="post">
                            <?php while ($row = $subcategorias->fetch_assoc()): ?>
                                <h5><?php echo $row['nombre_subcategoria']; ?></h5>
                                <input type="hidden" name="experiencia[<?php echo $row['id_subcategoria']; ?>][nombre]" value="<?php echo $row['nombre_subcategoria']; ?>">
            
                                <div class="periodos" data-subcategoria="<?php echo $row['id_subcategoria']; ?>">
                                    <div class="row mb-2">
                                        <div class="col">
                                            <label>Fecha de inicio:</label>
                                            <input type="date" name="experiencia[<?php echo $row['id_subcategoria']; ?>][fechas_inicio][]" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label>Fecha de fin:</label>
                                            <input type="date" name="experiencia[<?php echo $row['id_subcategoria']; ?>][fechas_fin][]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary add-periodo" data-subcat="<?php echo $row['id_subcategoria']; ?>">Agregar otra fecha</button>
                                <hr>
                            <?php endwhile; ?>
            
                            <div class="d-flex justify-content-between">
                                <a href="program.php" class="btn btn-outline-secondary">← Anterior</a>
                                <button type="submit" class="btn botones">Calcular →</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

                <?php if (!empty($mensaje)): ?>
                    <?php echo $mensaje; ?>
                <?php endif; ?>
            <?php $conn->close(); ?>
        </div>
    </div>
    <script>
        document.querySelectorAll('.add-periodo').forEach(btn => {
            btn.addEventListener('click', () => {
                const subcat = btn.getAttribute('data-subcat');
                const container = document.querySelector(`.periodos[data-subcategoria="${subcat}"]`);

                const nuevo = document.createElement('div');
                nuevo.classList.add('row', 'mb-2');
                nuevo.innerHTML = `
                    <div class="col">
                        <input type="date" name="experiencia[${subcat}][fechas_inicio][]" class="form-control">
                    </div>
                    <div class="col">
                        <input type="date" name="experiencia[${subcat}][fechas_fin][]" class="form-control">
                    </div>
                `;
                container.appendChild(nuevo);
            });
        });
    </script>
    
<?php include('../../includes/panel/footer.php'); ?>