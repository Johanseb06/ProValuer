<?php
session_start();
include('../config/db_config.php'); // Incluir la conexión a la base de datos

// Comprobar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario de forma segura
    $username = trim($_POST['email']);
    $password = $_POST['password'];

    // Preparar la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $username);  // "s" indica que el parámetro es una cadena
    $stmt->execute();
    $result = $stmt->get_result();

    // Comprobar si se encontró el usuario
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['clave'])) {
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['rol_usuario_fk'] = $user['rol_usuario_fk'];

            // Corregido: Usar correctamente la variable del nombre de usuario
            $nombre = mysqli_real_escape_string($conn, $_SESSION['nombre_usuario']);
            $conn->query("SET @usuario_actual := '$nombre'");

            // Redireccionar según el rol
            switch ($user["rol_usuario_fk"]) {
                case 1:
                    header("Location: ../user_roles/pages/profile.php");
                    break;
                case 2:
                    header("Location: ../user_roles/pages/profile.php");
                    break;
                default:
                    header("Location: ../user_roles/pages/profile.php");
                    break;
            }
            exit;
        } else {
            $_SESSION['mensaje'] = "Contraseña incorrecta.";
            header("Location: ../pages/signin.php");
            exit;
        }
    } else {
        $_SESSION['mensaje'] = "Usuario no encontrado.";
        header("Location: ../pages/signin.php");
        exit;
    }

    $stmt->close();
}

$conn->close();
