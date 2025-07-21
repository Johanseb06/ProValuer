<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 1) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Editar usuario'; // Aquí va el nombre de la sección actual, como 'Categoria', 'Contacto', etc

include("../../config/db_config.php");
include '../../includes/panel/header.php';

$id = $_GET["id"];
$sql = "SELECT * FROM usuarios WHERE id_usuario=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql1 = "SELECT * FROM roles";
$result1 = $conn->query($sql1);
?>



<div class="container mt-4">
    <h4>Editar Usuario</h4>

    <form action="../../users/actualizar.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre_usuario" class="form-control" value="<?= $row['nombre_usuario'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="documento" class="form-label">Documento:</label>
            <input type="text" name="documento" class="form-control" value="<?= $row['documento'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo:</label>
            <input type="email" name="correo" class="form-control" value="<?= $row['correo'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Selecciona un usuario:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">-- Selecciona --</option>
                <?php while ($row1 = $result1->fetch_assoc()): ?>
                    <option value="<?php echo $row1['id_rol']; ?>"><?php echo $row1['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
            <?php $conn->close(); ?>
        </div>

        <button type="submit" class="btn botones">Actualizar</button>
        <a href="user_manager.php" class="btn btn-secondary">Cancelar</a>
    </form>

</div>

<?php include('../../includes/panel/footer.php'); ?>