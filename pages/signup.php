<?php 
session_start();
include('../includes/public/header.php'); 
?>

<div class="content-fluid">
    <section class="login position-relative d-flex justify-content-center align-items-center">
        <img class="object-fit-cover" src="../assets/img/curri.jpeg" alt="curriculum">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75"></div>
        <div class="content-fluid position-absolute w-75">
            <div class="tarjeta row d-flex align-items-center justify-content-evenly text-white shadow bg-secondary bg-opacity-25 rounded py-5 mt-5 px-3">
                <div class="col-sm-12 col-md-6 col-lg-5">
                    <p class="text-uppercase fw-medium mb-3">Registrarse</p>
                    <h3 class="mb-4 fw-semibold">Accede a las herramientas que <span class="texto-verde">simplifican</span> y agilizan la gestión de procesos</h3>
                    <p class="mb-3 fs-6">¿Ya tienes una cuenta?</p>
                    <a href="./signin.php" class="boton btn rounded-pill py-2 px-4 my-3 my-lg-0 me-2 flex-shrink-0">Iniciar sesión</a>
                    <a href="../index.php" class="boton btn rounded-pill py-2 px-4 my-3 my-lg-0 flex-shrink-0">Cancelar</a>
                </div>
                <div class="col-sm-10 col-md-6 col-lg-5">

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Oh no!</strong> <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>

                    <h3 class="mb-4">Ingresa tus datos</h3>
                    <form class="d-grid row-gap-3 needs-validation" action="../auth/register.php" method="POST" id="miFormulario" novalidate>
                        
                        <div>
                            <input type="text" class="form-control" id="input-name" name="name" placeholder="Nombre Completo" required value="<?php echo isset($_SESSION['old_data']['name']) ? htmlspecialchars($_SESSION['old_data']['name']) : ''; ?>">
                        </div>
                        
                        <div class="">
                        <input type="number" class="form-control" id="input-number" name="number" placeholder="Documento" required value="<?php echo isset($_SESSION['old_data']['number']) ? htmlspecialchars($_SESSION['old_data']['number']) : ''; ?>">
                        </div>

                        <div class="">
                        <input type="email" class="form-control" id="input-email" name="email" placeholder="Correo Electrónico" required value="<?php echo isset($_SESSION['old_data']['email']) ? htmlspecialchars($_SESSION['old_data']['email']) : ''; ?>">
                            <div class="invalid-feedback">Introduce un correo válido.</div>
                        </div>

                        <div class="">
                            <input type="password" class="form-control" id="input-password" name="password" placeholder="Contraseña" required minlength="6">
                            <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                        </div>

                        <div class="">
                            <input type="password" class="form-control" id="input-cpassword" name="cpassword" placeholder="Confirmar Contraseña" required minlength="6">
                            <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                        </div>

                        <button type="submit" name="register_btn" class="boton btn rounded-pill py-2 px-4 my-3 my-lg-0 flex-shrink-0">Registrarme</button>
                    </form>
                </div>
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
        </div>
    </section>
</div> 

<?php include('../includes/public/footer.php'); ?>