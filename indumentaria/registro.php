<!-- Formulario de Registro -->
<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Registro</title>
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">
    <div class="bg-dark p-3 rounded-1" style="width: 35rem;">
        <h2 class="text-center text-light">Registro de Usuario</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="procesar_registro.php" method="POST">
            <div class="input-group mt-5 justify-content-center">
                <input class="form-control" type="text" placeholder="Nombre" name="nombre" required>
            </div>
            <div class="input-group mt-3 justify-content-center">
                <input class="form-control" type="email" placeholder="Correo Electrónico" name="email" required>
            </div>
            <div class="input-group mt-3 justify-content-center">
                <input class="form-control" type="password" placeholder="Contraseña" name="contraseña" required>
            </div>
            <div class="input-group mt-3 justify-content-center">
                <input class="form-control" type="password" placeholder="Confirmar Contraseña" name="confirmar_contraseña" required>
            </div>
            <center><input type="submit" class="btn btn-danger w-50 mt-5" value="Registrarse"></center>
        </form>

        <div class="mt-3 text-center text-light">
            <p>¿Ya tenes una cuenta? <a href="login.php" class="text-decoration-none">Inicia sesión</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous">
    </script>

    <style>
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }
    </style>
</body>
</html>
