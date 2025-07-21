<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/db_config.php';
include '../includes/init.php';

if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Programa';

if (!isset($_SESSION['formulario']['postulante'])) {
    // die("⚠️ No hay un postulante seleccionado.");
    $_SESSION['mensaje'] = "⚠️ No hay un postulante seleccionado.";
    exit;
}
$postulante = $_SESSION['formulario']['postulante'];

if (!isset($_SESSION['formulario']['perfil'])) {
    // die("⚠️ No hay un perfil seleccionado.");
    $_SESSION['mensaje'] = "⚠️ No hay un perfil seleccionado.";
    exit;
}
$perfil = $_SESSION['formulario']['perfil'];
?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <div class="step">1. Postulante</div>
                <div class="step">2. Perfil</div>
                <div class="step active">3. Programa</div>
                <div class="step">4. Experiencia</div>
                <div class="step">5. Categorias</div>
                <div class="step">6. Resumen</div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4>Añadir o Seleccionar Programa</h4>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 50%">50%</div>
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
                        </ul>
                    </div>

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Oh no!</strong> <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>

                    <!-- Campo de búsqueda -->
                    <div class="">
                        <label for="buscar_programa" class="form-label">Buscar programa existente: </label>
                        <input type="text" class="form-control" id="buscar_programa" placeholder="Escribe el nombre..." autocomplete="off">
                        <div id="resultados_busqueda" class="p-2"></div>
                    </div>
                    <!-- Formulario si no se selecciona ninguno -->
                    <form id="form_crear" action="../controllers/save_program.php" method="post" style="display:none;">
                        <input type="hidden" name="modo" value="nuevo">

                        <div class="mb-3">
                            <label for="new_program" class="form-label">Nuevo programa</label>
                            <input type="text" class="form-control" id="new_program" name="programa" required>
                        </div>
                        <input type="hidden" name="perfil" id="perfil_oculto">

                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_final" class="form-label">Fecha de Finalización</label>
                            <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn botones">Guardar y continuar</button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-start">
                        <a href="profile.php" class="btn btn-outline-secondary">← Anterior</a>
                    </div>

                    <!-- Formulario oculto para seleccionar existente -->
                    <form id="form_existente" action="../controllers/save_program.php" method="post" style="display:none;">
                        <input type="hidden" name="modo" value="existente">
                        <input type="hidden" name="id_programa" id="id_programa_seleccionado">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn botones">Siguiente →</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("buscar_programa").addEventListener("input", function() {
        const consulta = this.value;

        if (consulta.length < 2) {
            document.getElementById("resultados_busqueda").innerHTML = "";
            return;
        }

        fetch(`../ajax/buscar_programa.php?nombre=${encodeURIComponent(consulta)}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.length === 0) {
                    html += '<p><em>No encontrado. Puedes crear uno nuevo.</em></p>';
                    document.getElementById("form_crear").style.display = 'block';
                    document.getElementById("new_program").value = consulta;
                    document.getElementById("form_existente").style.display = 'none';
                } else {
                    html += '<ul>';
                    data.forEach(p => {
                        const texto = `${p.nombre_programa} (${p.fecha_inicial} al ${p.fecha_final})`;
                        html += `<li onclick="seleccionarPrograma('${p.id_programa}', '${texto}')">${texto}</li>`;

                    });
                    html += '</ul>';
                    document.getElementById("form_crear").style.display = 'none';
                    document.getElementById("form_existente").style.display = 'none';
                }
                document.getElementById("resultados_busqueda").innerHTML = html;
            });
    });

    function seleccionarPrograma(id, textoCompleto) {
        document.getElementById("buscar_programa").value = textoCompleto;
        document.getElementById("id_programa_seleccionado").value = id;
        document.getElementById("form_existente").style.display = 'block';
        document.getElementById("form_crear").style.display = 'none';
        document.getElementById("resultados_busqueda").innerHTML = `<p><strong>Seleccionado:</strong> ${textoCompleto}</p>`;
    }
</script>

<?php include('../../includes/panel/footer.php'); ?>