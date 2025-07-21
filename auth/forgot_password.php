<?php
session_start();
include('../config/db_config.php'); // Incluir la conexión a la base de datos

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar contraseña</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link id="pagestyle" href="/provaluer/assets/css/style.css" rel="stylesheet" />
  <link rel="icon" href="/provaluer/assets/img/logo.png">
</head>
<body class="bg-luz">
  <div class="container-fluid">
    <section class="d-flex justify-content-center align-items-center vh-100">
      <div class="w-100 px-3">
        <div class="row justify-content-center">
          <div class="col-lg-3 bg-white text-dark rounded-4 shadow p-4">
            <h3 class="text-center mb-3">Recuperar contraseña</h3>
            <div class="text-center mb-3">
              <img src="../assets/img/password.jpg" alt="image" class="img-fluid border" style="max-width: 150px;">
            </div>
            <p class="text-center text-muted mb-4">
              Ingresa tu correo y te enviaremos un código para restablecer tu contraseña.
            </p>
            <form action="send_reset_code.php" method="POST">
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" id="email" placeholder="Ingresa tu correo" required>
              </div>
              <div class="d-grid mb-2">
                <button type="submit" class="btn botones text-white rounded-pill py-2">Enviar código</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>

</body>
</html>
