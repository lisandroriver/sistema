<?php
// Incluir la conexión a la base de datos
include 'db.php';

// Verificar si hay errores de autenticación
$errors = [];
if (isset($_GET['error'])) {
    $error_code = $_GET['error'];
    if ($error_code == 'contraseña_incorrecta') {
        $errors[] = 'La contraseña es incorrecta.';
    } elseif ($error_code == 'usuario_no_existente') {
        $errors[] = 'El usuario no existe.';
    }
}
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">
    <div class="bg-dark p-3 rounded-1" style="width: 35rem;">
        <!-- Mostrar los errores si existen -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="metadatos.php" method="POST">
            <div class="text-danger text-center fs-1 fw-bold">Login</div>
            <br>
            <div class="input-group mt-3 justify-content-center">
                <center><input class="form-control" type="text" placeholder="Username" name="user" required></center>
            </div>
            <div class="input-group mt-3 justify-content-center">
                <center><input class="form-control" type="password" placeholder="Password" name="contra" required></center>
            </div>
            <center><input type="submit" class="btn btn-danger w-50 mt-5" value="LOG IN"></center>
        </form>

        <div class="d-flex justify-content-center mt-3">
            <p class="text-light" style="font-size: 0.9rem;">¿No tenes una cuenta? 
                <a href="registro.php" class="btn btn-outline-danger btn-sm">Regístrate</a>
            </p>
        </div>
    </div>
</body>
</html>
