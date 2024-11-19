<?php
// Incluir archivo de conexión a la base de datos
include 'db.php';

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si los campos 'nombre', 'email', 'telefono' y 'direccion' están presentes en $_POST
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

    // Validar los campos obligatorios
    if ($nombre && $email && $telefono && $direccion) {
        try {
            // Preparar la consulta SQL para insertar el nuevo cliente
            $sql = "INSERT INTO clientes (nombre, email, telefono, direccion) 
                    VALUES (:nombre, :email, :telefono, :direccion)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Redirigir al listado de clientes con un mensaje de éxito
                header("Location: clientes.php?mensaje=Cliente agregado exitosamente");
                exit();
            } else {
                $error = "Error al agregar el cliente.";
            }
        } catch (PDOException $e) {
            $error = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Agregar Cliente</title>
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">
    <div class="bg-dark p-4 rounded" style="width: 40rem;">
        <h2 class="text-center mb-4">Agregar Cliente</h2>

        <!-- Mostrar el mensaje de error, si existe -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Formulario para agregar un cliente -->
        <form action="agregar_clientes.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Agregar Cliente</button>
        </form>
    </div>
</body>
</html>
