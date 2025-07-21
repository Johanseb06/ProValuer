<?php
session_start();
include('../config/db_config.php'); // Conexión a la base de datos

// Si se envió el formulario con el código
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['correo'] ?? '';
    $code = $_POST['code'] ?? '';

    // Verificamos si hay correo y código
    if ($email && $code) {
        // Buscar usuario
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();

        if ($usuario) {
            // Verificar código
            $stmt = $conn->prepare("SELECT * FROM password_reset WHERE usuario_id = ? AND code = ? ORDER BY created_at DESC LIMIT 1");
            $stmt->bind_param("is", $usuario['id_usuario'], $code);
            $stmt->execute();
            $reset = $stmt->get_result()->fetch_assoc();

            if ($reset) {
                // Código válido, mostrar formulario de nueva contraseña
                ?>
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Nueva Contraseña</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <link id="pagestyle" href="/provaluer/assets/css/style.css" rel="stylesheet">
                    <link rel="icon" href="/provaluer/assets/img/logo.png">
                </head>
                <body class="bg-luz">
                    <div class="container-fluid">
                        <section class="d-flex justify-content-center align-items-center vh-100">
                            <div class="w-100 px-3">
                                <div class="row justify-content-center">
                                    <div class="col-lg-3 bg-white text-dark rounded-4 shadow p-4">
                                        <h3 class="text-center mb-3">Establecer nueva contraseña</h3>
                                        <div class="text-center mb-3">
                                            <img src="../assets/img/password.jpg" alt="image" class="img-fluid border" style="max-width: 150px;">
                                        </div>
                                        <form method="POST" action="update_password.php">
                                            <input type="hidden" name="usuario_id" value="<?= $usuario['id_usuario'] ?>">
                                            <div class="mb-3">
                                                <label>Nueva contraseña</label>
                                                <input type="password" name="new_password" class="form-control" placeholder="Ingresa la nueva contraseña"required>
                                            </div>
                                            <button class="btn botones text-white rounded-pill py-2">Actualizar contraseña</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </body>
                </html>
                <?php
                exit;
            } else {
                $mensaje = "Código inválido o expirado.";
            }
        } else {
            $mensaje = "Correo no válido.";
        }
    } else {
        $mensaje = "Por favor, ingresa tu correo y el código.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verificar Código</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link id="pagestyle" href="/provaluer/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/provaluer/assets/img/logo.png">

</head>
<body class="bg_luz">
    <div class="container-fluid">
        <section class="d-flex justify-content-center align-items-center vh-100">
            <div class="w-100 px-3">
                <div class="row justify-content-center">
                    <div class="col-lg-3 bg-white text-dark rounded-4 shadow p-4">
                        <h3 class="text-center mb-3">Verificación de Código</h3>
                        <div class="text-center mb-3">
                            <img src="../assets/img/password.jpg" alt="image" class="img-fluid border" style="max-width: 150px;">
                        </div>
                        <p class="text-center text-muted mb-4">
                            Introduce el código que recibiste por correo electrónico.
                        </p>
                        <?php if (!empty($mensaje)) : ?>
                            <div class="alert alert-danger"><?= $mensaje ?></div>
                        <?php endif; ?>

                        <form method="POST" action="reset_password.php">
                            <div class="mb-3">
                                <label>Correo electrónico</label>
                                <input type="email" name="correo" class="form-control" placeholder="Ingresa tu correo" required>
                            </div>
                            <div class="mb-3">
                                <label>Código de verificación</label>
                                <input type="text" name="code" class="form-control" placeholder="Ingresa el codigo" required>
                            </div class="d-grid mb-2">
                            <button class="btn botones text-white rounded-pill py-2">Verificar</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>


