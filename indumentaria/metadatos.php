<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['user'];
    $contraseña = $_POST['contra'];

    // Preparar la consulta SQL
    $sql = "SELECT * FROM usuarios WHERE email = :usuario";
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro correctamente
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    // Verificar si se encontró el usuario
    if ($stmt->rowCount() > 0) {
        // Usuario encontrado
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if (password_verify($contraseña, $user['password'])) {
            // Iniciar sesión y redirigir
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: index.php");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: login.php?error=contraseña_incorrecta");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: login.php?error=usuario_no_existente");
        exit();
    }
}
?>
