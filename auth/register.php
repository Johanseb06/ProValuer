<?php 
session_start();
include('../config/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_btn'])) {
    $name      = mysqli_real_escape_string($conn, $_POST['name']);  
    $number    = mysqli_real_escape_string($conn, $_POST['number']);  
    $email     = mysqli_real_escape_string($conn, $_POST['email']);  
    $password  = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    $active = 1;
    $rol = 3;

    // Verificar si el correo ya existe
    $checkEmail = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $_SESSION['mensaje'] = "El usuario ya existe";
        $_SESSION['old_data'] = [
            'name' => $_POST['name'],
            'number' => $_POST['number'],
            'email' => $_POST['email']
        ];
        header('Location: ../pages/signup.php');
        exit;
    } else {
        if ($password === $cpassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $nombre = $_SESSION['name'];
            mysqli_query($conn, "SET @usuario_actual := '" . mysqli_real_escape_string($conn, $name) . "'");;

            // Preparamos la consulta
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, documento, correo, activo, clave, rol_usuario_fk) VALUES (?, ?, ?, ?, ?, ?)");
            // Vinculamos los valores
            $stmt->bind_param("sisisi", $name, $number, $email, $active, $hashedPassword, $rol);
            // Ejecutar la consulta
            if ($stmt->execute()) {
                unset($_SESSION['old_data']);
                $_SESSION['mensaje'] = "Registro exitoso, ya puede iniciar sesión";
                header('Location: ../pages/signin.php');
                exit;
            } else {
                $_SESSION['mensaje'] = "Error al registrar el usuario";
                $_SESSION['old_data'] = [
                    'name' => $_POST['name'],
                    'number' => $_POST['number'],
                    'email' => $_POST['email']
                ];
                header('Location: ../pages/signup.php');
                exit;
            }

            $stmt->close();
        } else {
            $_SESSION['mensaje'] = "Las contraseñas no coinciden";
            $_SESSION['old_data'] = [
                'name' => $_POST['name'],
                'number' => $_POST['number'],
                'email' => $_POST['email']
            ];
            header('Location: ../pages/signup.php');
            exit;
        }
    }

    $checkEmail->close();
    $conn->close();
}
?>