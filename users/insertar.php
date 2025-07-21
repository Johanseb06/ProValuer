<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config/db_config.php");
// usuario responsable
mysqli_query($conn, "SET @usuario_actual := '".mysqli_real_escape_string($conn, $_SESSION['nombre_usuario'])."'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $number = $_POST["number"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $active = 1;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM usuarios WHERE correo = '$email'";
    //var_dump($usuario);
    // var_dump($sql);

    $result = $conn->query($sql);
    //var_dump($result);

    if ($result->num_rows > 0) {

        $mensaje = "El correo ya existe";
        header("Location: ../rol_admin/pages/add_user.php?mensaje=" . urlencode($mensaje));
    } else {

        // Preparar y bindear
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, documento, correo, activo, clave, rol_usuario_fk) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("sisisi", $name, $number, $email, $active, $hashedPassword, $role);

        $mensaje = "Registro exitoso";

        // Ejecutar la consulta
        if ($stmt->execute()) {
            header("Location: ../rol_admin/pages/user_manager.php");
            $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";
        } else {
            $mensaje = "Error: " . $stmt->error;
        }

        // Cerrar conexión
        $stmt->close();
        $conn->close();
    }
}