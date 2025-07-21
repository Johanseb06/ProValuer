<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/db_config.php';
include '../includes/init.php';

if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Añadir postulante';
?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 mb-4">
                        <?php if (isset($_SESSION['guardado'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $_SESSION['guardado']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                            <?php unset($_SESSION['guardado']); ?>
                        <?php endif; ?>
                        <h2>Bienvenido a Provaluer</h2>
                        <p>Sigue estos 6 pasos para conocer el puntaje:</p>
                    </div>
                    <div class="col-md-4">
                        <div class="list-group">
                            <div class="step active">1. Postulante</div>
                            <div class="step">2. Perfil</div>
                            <div class="step">3. Programa</div>
                            <div class="step">4. Experiencia</div>
                            <div class="step">5. Categorias</div>
                            <div class="step">6. Resumen</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h4>Añadir o Seleccionar Postulante</h4>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 16%">16%</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <label for="buscar_postulante" class="form-label">Buscar postulantes existente: </label>
                                    <input type="number" class="form-control" id="buscar_postulante" placeholder="Escribe el documento..." autocomplete="off">
                                    <div id="resultados_busqueda" class="p-2"></div>
                                </div>

                                <?php if (isset($_SESSION['mensaje'])): ?>
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>Oh no!</strong> <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php unset($_SESSION['mensaje']); ?>
                                <?php endif; ?>

                                <form action="../controllers/save_postulant.php" method="POST" id="form_crear" class="needs-validation" style="display:none;" novalidate>
                                    <input type="hidden" name="modo" value="nuevo">
                                    <div class="mb-3">
                                        <label for="new_name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="new_name" name="name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="document" class="form-label">Documento</label>
                                        <input type="number" class="form-control" id="document" name="document">
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn botones">Guardar y continuar</button>
                                    </div>
                                </form>
                                <!-- Formulario oculto para seleccionar existente -->
                                <form id="postulante_existente" action="../controllers/save_postulant.php" method="post" style="display:none;">
                                    <input type="hidden" name="modo" value="existente">
                                    <input type="hidden" name="id_postulante" id="id_postulante_seleccionado">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn botones">Elegir y continuar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <?php if (!empty($mensaje)): ?>
                            <?php echo $mensaje; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("buscar_postulante").addEventListener("input", function() {
        const consulta = this.value;

        if (consulta.length < 2) {
            document.getElementById("resultados_busqueda").innerHTML = "";
            return;
        }

        fetch(`../ajax/buscar_postulante.php?documento=${encodeURIComponent(consulta)}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.length === 0) {
                    html += '<p><em>No encontrado. Puedes agregar uno nuevo.</em></p>';
                    document.getElementById("form_crear").style.display = 'block';
                    document.getElementById("document").value = consulta;
                    document.getElementById("postulante_existente").style.display = 'none';
                } else {
                    html += '<div class="list-group list-group-flush">';
                    data.forEach(p => {
                        const texto = `${p.nombre_postulante} ${p.documento}`;
                        html += `<a class="list-group-item list-group-item-action" onclick="seleccionarPostulante('${p.id_postulante}', decodeURIComponent('${encodeURIComponent(texto)}'), ${p.documento})">${p.nombre_postulante}</a>`;

                    });
                    html += '</div>';
                    document.getElementById("form_crear").style.display = 'none';
                    document.getElementById("postulante_existente").style.display = 'none';
                }
                document.getElementById("resultados_busqueda").innerHTML = html;
            });
    });

    function seleccionarPostulante(id, textoCompleto, documento) {
        document.getElementById("buscar_postulante").value = documento;
        document.getElementById("id_postulante_seleccionado").value = id;
        document.getElementById("postulante_existente").style.display = 'block';
        document.getElementById("form_crear").style.display = 'none';
        document.getElementById("resultados_busqueda").innerHTML = `<p class="text-capitalize"><strong>Seleccionado:</strong> ${textoCompleto}</p>`;
    }
</script>

<?php include('../../includes/panel/footer.php'); ?>