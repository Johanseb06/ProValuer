<?php
session_start(); 

$mensaje = "";

// Recuperar y limpiar el mensaje de sesión
if (!empty($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']); 
}
?>
<?php include('../includes/public/header.php'); ?>

<div class="content-fluid">
    <section class="login position-relative d-flex justify-content-center align-items-center">
        <img class="object-fit-cover" src="../assets/img/curri.jpeg" alt="Imagen de fondo">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75"></div>

        <div class="content-fluid position-absolute">
            <div class="tarjeta row d-flex align-items-center justify-content-evenly text-white shadow rounded py-5 mt-5">
                
                <!-- Columna de bienvenida -->
                <div class="col-sm-10 col-md-6 col-lg-5">
                    <p class="text-uppercase fw-medium mb-3">Iniciar sesión</p>
                    <h3 class="mb-3 fw-semibold">Accede a tu panel de <span class="texto-verde">evaluación</span>.</h3>
                    <p class="fs-6">¿No tienes una cuenta?</p>
                    <a href="./signup.php" class="boton btn rounded-pill px-4 py-2 me-2">Registrarme</a>
                    <a href="../index.php" class="boton btn rounded-pill px-4 py-2">Cancelar</a>
                </div>

                <!-- Columna del formulario -->
                <div class="col-sm-10 col-md-6 col-lg-5">
                    <h3 class="mb-4">Ingresa tus datos</h3>
                    
                    <!-- Formulario de inicio de sesión -->
                    <form action="../auth/login.php" method="POST" id="loginForm" novalidate class="d-grid row-gap-4">
                        
                        <div>
                            <input type="email" class="form-control" id="input-email" name="email" placeholder="Correo Electrónico" required>
                            <div class="invalid-feedback">Introduce un correo válido.</div>
                        </div>

                        <div>
                            <input type="password" class="form-control" id="input-password" name="password" placeholder="Contraseña" required minlength="6">
                            <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                        </div>

                        <button type="submit" class="boton btn rounded-pill py-2 px-4 my-3 my-lg-0 flex-shrink-0">Iniciar sesión</button>

                        <!-- Mostrar mensaje si existe -->
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                                <?= htmlspecialchars($mensaje) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                    </form>

                    <!-- Enlace para recuperar contraseña -->
                    <div class="mt-2 text-center">
                        <a href="../auth/forgot_password.php" class="text-white text-decoration-underline">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript para validación de formulario -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", (event) => {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add("was-validated");
    });
});
</script>

<?php include('../includes/public/footer.php'); ?>
