<?php 
session_start();
if (!isset($_SESSION["id_usuario"]) || !in_array($_SESSION["rol_usuario_fk"], [1, 2, 3])) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Perfil';

include('../../includes/panel/header.php'); 
include("../../config/db_config.php"); 

$id = $_SESSION["id_usuario"];
$sql = "SELECT * FROM usuarios WHERE id_usuario=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


?>
<div class="container mt-5">
    <h2 class="text-center">Hola <strong class="texto-verde"><?php echo htmlspecialchars($_SESSION["nombre_usuario"]); ?></strong> Estás en ProValuer, nos alegra tenerte aquí</h2>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header text-black rounded-top-4">
                    <h4 class="mb-0">Usuario</h4>
                </div>
                <div class="card-body bg-light rounded-bottom-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="bi bi-person-fill me-2"></i>Nombre</label>
                        <p class="form-control-plaintext ps-3"><?= htmlspecialchars($row["nombre_usuario"] ?? '') ?></p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="bi bi-envelope-fill me-2"></i>Correo electrónico</label>
                        <p class="form-control-plaintext ps-3"><?= htmlspecialchars($row["correo"] ?? '') ?></p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="bi bi-card-text me-2"></i>Documento</label>
                        <p class="form-control-plaintext ps-3"><?= htmlspecialchars($row["documento"] ?? '') ?></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/panel/footer.php');?>