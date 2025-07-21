<?php 
session_start();
if (!isset($_SESSION["id_usuario"]) || !in_array($_SESSION["rol_usuario_fk"], [1, 2, 3])) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Contacto';
?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>contenido...</h2>
        </div>
    </div>
</div>

<?php include('../../includes/panel/footer.php'); ?>
