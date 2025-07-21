<?php
session_start();
include('../config/db_config.php'); // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $new_password = $_POST['new_password'];

    // Validar entrada
    if (!empty($usuario_id) && !empty($new_password)) {
        // Hashear la nueva contraseña (muy recomendable)
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Actualizar contraseña en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET clave = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $hashed_password, $usuario_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ Contraseña actualizada correctamente');
                    window.location.href = '../pages/signin.php';
                    </script>";
        } else {
            echo "<script>
                    alert('❌ Error al actualizar la contraseña.');
                    window.history.back(); // Regresa a la página anterior
                    </script>";
        }
    } else {
        echo "<script>
                alert('❌ Datos incompletos.');
                window.history.back();
                </script>";
    }
}
