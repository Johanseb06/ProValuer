<?php 
session_start();
if (!isset($_SESSION["id_usuario"]) || !in_array($_SESSION["rol_usuario_fk"], [1, 2, 3])) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Ajustes';
include('../../includes/panel/header.php');
include("../../config/db_config.php"); 

$id = $_SESSION["id_usuario"];
$sql = "SELECT * FROM usuarios WHERE id_usuario=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST["id_usuario"]);
    $nombre = $conn->real_escape_string($_POST["nombre_usuario"]);
    $correo = $conn->real_escape_string($_POST["correo"]);
    $numero = $conn->real_escape_string($_POST["documento"]);

    $sql_update = "UPDATE usuarios SET nombre_usuario = '$nombre', correo = '$correo', documento = '$numero' WHERE id_usuario = $id";

    if ($conn->query($sql_update) === TRUE) {
        $mensaje = "¡Datos actualizados correctamente!";                                          
        $_SESSION["nombre_usuario"] = $nombre;
        $_SESSION["correo"] = $correo;
        $_SESSION["documento"] = $numero;
    } else {
        $mensaje = "Error al actualizar: " . $conn->error;
    }
}

?>

<div class="container mt-4">
    <div class="row">
        <h2>Editar Usuario</h2>
        <form method="POST" action="../../users/actualizar_user.php">
            <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?? '' ?>">

                <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" value="<?= htmlspecialchars($row["nombre_usuario"] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="correo" id="correo" value="<?= htmlspecialchars($row["correo"] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="documento" class="form-label">Documento</label>
                    <input type="text" class="form-control" name="documento" id="documento" value="<?= htmlspecialchars($row["documento"] ?? '') ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn botones">Guardar Cambios</button>
                </div>
        </form>
    </div>
</div>

<?php include('../../includes/panel/footer.php');?>