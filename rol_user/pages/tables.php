<?php session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}

?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2> Tables</h2>
        </div>
    </div>
</div>

<?php include('../../includes/panel/footer.php'); ?>