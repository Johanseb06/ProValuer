<?php
session_start();
include('../config/db_config.php'); // Conexión a la BD

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
    $email = $_POST['correo'];

    // Verificar si el correo existe
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($usuario = $result->fetch_assoc()) {
        $code = rand(100000, 999999);

        // Limpiar códigos antiguos (más de 1 hora)
        $conn->query("DELETE FROM password_reset WHERE created_at < NOW() - INTERVAL 1 HOUR");

        // Asegurar existencia de tabla
        $conn->query("CREATE TABLE IF NOT EXISTS password_reset (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT,
            code VARCHAR(6),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Insertar nuevo código
        $stmt = $conn->prepare("INSERT INTO password_reset (usuario_id, code) VALUES (?, ?)");
        $stmt->bind_param("is", $usuario['id_usuario'], $code);
        $stmt->execute();

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'provaluer.sena@gmail.com';
            $mail->Password = 'ywir cvlr ffua thyj'; // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('provaluer.sena@gmail.com', 'Soporte - Recuperación de Contraseña');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Código de Recuperación de Contraseña';
            $mail->Body = "
                <p>Hola,</p>
                <p>Tu código de verificación para recuperar tu contraseña es:</p>
                <h2 style='color:blue;'>$code</h2>
                <p>Este código es válido por 1 hora.</p>
                <p>Si no solicitaste este código, ignora este mensaje.</p>
                <br><p>— Soporte Técnico</p>
            ";

            $mail->send();

            // Redirigir al formulario para ingresar el código
            header("Location: reset_password.php?email=" . urlencode($email));
            exit;

        } catch (Exception $e) {
            header("Location: forgot_password.php?error=correo_no_enviado");
            exit;
        }

    } else {
        // Mostrar alerta si el correo no existe
        echo "<script>
            alert('El correo no está registrado.');
            window.location.href = 'forgot_password.php';
        </script>";
        exit;
    }
} else {
    header("Location: forgot_password.php");
    exit;
}
