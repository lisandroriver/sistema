<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['contraseña'];
    $confirmar_password = $_POST['confirmar_contraseña'];

    // Validación de campos
    if (empty($nombre) || empty($email) || empty($password) || empty($confirmar_password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirmar_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el correo electrónico ya está registrado
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            // Encriptar la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                // Redirigir al login después de un registro exitoso
                header("Location: login.php");
                exit();
            } else {
                $error = "Hubo un error al registrar el usuario. Intenta de nuevo.";
            }
        }
    }
}
?>