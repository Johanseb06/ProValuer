<?php
if (empty($_GET['mensaje'])) {

    $_GET['mensaje'] = "";
    $mensaje = $_GET['mensaje'];
} else {
    $mensaje = $_GET['mensaje'];
}

session_start();
if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 1) {
    header("Location: /provaluer/index.php");
    exit();
}

$nombre_page = 'Añadir usuario'; // Aquí va el nombre de la sección actual, como 'Categoria', 'Contacto', etc

include '../../config/db_config.php';
include '../../includes/panel/header.php';

$sql = "SELECT id_rol, nombre FROM roles";
$result = $conn->query($sql)
?>



<div class="container mt-3">


    <h6>Agregar Usuario</h6>

    <!-- Formulario -->
    <form action="../../users/insertar.php" method="POST" id="miFormulario" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="name" placeholder="Tu Nombre">
        </div>
        <div class="mb-3">
            <label for="number" class="form-label">Documento</label>
            <input type="number" class="form-control" id="number" name="number" placeholder="Tu documento" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="nombre@correo.com" required>
            <div class="invalid-feedback">Introduce un correo válido.</div>
        </div>
        <div class="mt-2">
            <p class="text-danger"><?php if (!empty($mensaje)): ?>
                <?php echo $mensaje; ?>

            <?php endif; ?></p>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Selecciona un usuario:</label>
            <select name="role" id="role" class="form-control">
                <option value="">-- Selecciona --</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_rol']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
            <?php $conn->close(); ?>

        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="********" required minlength="6">
            <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
        </div>

        <div class="mb-3 form-check">
            <!-- Mostrar mensaje de éxito o error -->


            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
            <label class="form-check-label" for="terms">Acepto los términos y condiciones</label>
        </div>
        <button type="submit" class="btn botones">Agregar</button>
        <a href="user_manager.php" class="btn btn-secondary">Cancelar</a>
    </form>
    <br>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("miFormulario");
            form.addEventListener("submit", (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated"); // Agrega estilos de Bootstrap para la validación
            });
        });
    </script>
</div>

<?php include('../../includes/panel/footer.php'); ?>