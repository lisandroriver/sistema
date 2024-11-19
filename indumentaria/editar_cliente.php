<?php
// Incluir archivo de conexión a la base de datos
include 'db.php';
session_start();

// Inicializar la variable de error
$error = '';

// Verificar si se ha pasado un ID por GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del cliente por ID
    try {
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el cliente existe
        if (!$cliente) {
            $error = "Cliente no encontrado.";
        }
    } catch (PDOException $e) {
        $error = "Error al obtener los datos: " . $e->getMessage();
    }
} else {
    $error = "ID de cliente no proporcionado.";
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    // Validar los campos obligatorios
    if ($nombre && $email && $telefono && $direccion) {
        try {
            // Preparar la consulta SQL para actualizar el cliente
            $sql = "UPDATE clientes SET nombre = :nombre, email = :email, telefono = :telefono, direccion = :direccion WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':id', $id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Redirigir a la lista de clientes con un mensaje de éxito
                header("Location: clientes.php?mensaje=Cliente actualizado exitosamente");
                exit();
            } else {
                $error = "Error al actualizar el cliente.";
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
    <title>Editar Cliente</title>
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">
    <div class="bg-dark p-4 rounded" style="width: 40rem;">
        <h2 class="text-center mb-4">Editar Cliente</h2>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Formulario de edición -->
        <?php if ($cliente): ?>
        <form action="editar_cliente.php?id=<?= $cliente['id'] ?>" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Actualizar Cliente</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
