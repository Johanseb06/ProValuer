<?php 
session_start();
if (!isset($_SESSION["id_usuario"]) || !in_array($_SESSION["rol_usuario_fk"], [1, 2, 3])) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Sobre nosotros';
?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow border-0 w-100 " style="max-width: 1000px;">
        <div class="card-body px-4 px-md-5 py-5">
            <!-- Sección Misión -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-7 mb-3 mb-lg-0">
                    <h3 class="fw-bold texto-verde mb-3">Nuestra Misión</h3>
                    <p class="mb-2">
                        ProValuer es una herramienta digital desarrollada para optimizar y agilizar el proceso de selección de instructores en el SENA. 
                        Evalúa objetivamente a los postulantes mediante un formulario estructurado, permitiendo decisiones más justas, transparentes y rápidas.
                    </p>
                    <p class="mb-0">
                        Esta solución innovadora contribuye directamente a mejorar la calidad educativa, asegurando que los mejores perfiles lleguen a ser instructores. 
                        ProValuer refleja cómo la tecnología puede ser aliada clave en la transformación institucional.
                    </p>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="../../assets/img/logo.png" class="img-fluid rounded" style="max-width: 250px;" alt="Equipo ProValuer">
                </div>
            </div>

            <!-- Sección Historia -->
            <div class="row align-items-center">
                <div class="col-lg-7 order-lg-2 mb-3 mb-lg-0">
                    <h3 class="fw-bold texto-verde mb-3">Nuestra Historia</h3>
                    <p class="mb-2">
                        ProValuer fue diseñado y desarrollado por un equipo de aprendices del SENA comprometidos con la innovación: 
                        <strong>Eilene Bobadilla, Johan Sebastián, Katerine Espinosa</strong> y <strong>Carlos Muñoz</strong>.
                    </p>
                    <p class="mb-0">
                        Nacido como un proyecto formativo, ProValuer representa el poder del talento joven aplicado a retos reales del entorno institucional. 
                        Con pasión, creatividad y compromiso, este equipo logró construir una herramienta útil y con impacto.
                    </p>
                </div>
                <div class="col-lg-5 order-lg-1 text-center">
                    <img src="../../assets/img/about.png" class="img-fluid rounded" style="max-width: 250px;" alt="Historia ProValuer">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/panel/footer.php'); ?>
