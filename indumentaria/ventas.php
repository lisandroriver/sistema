<?php
// Incluir archivo de conexión a la base de datos y funciones necesarias
include 'db.php';

// Obtener ventas desde la base de datos
$sql = "SELECT v.id, v.fecha, p.nombre AS producto, v.cantidad, v.total 
        FROM ventas v 
        JOIN productos p ON v.producto_id = p.id 
        ORDER BY v.fecha DESC";
$stmt = $conn->query($sql);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="menu">
        <div class="menu-logo">
            <a href="index.php">Sistema de Gestión de Productos</a>
        </div>
        <ul class="menu-links" id="menu-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="agregar.php">Agregar</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="reportes.php">Reportes</a></li>
            <li><a href="cerrarsesion.php">Salir</a></li>
        </ul>
    </nav>

    <!-- Contenedor para mostrar ventas -->
    <div class="container mt-5 bg-dark">
        <h2 class="text-center mb-4">Lista de Ventas</h2>

        <!-- Tabla de ventas -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?= htmlspecialchars($venta['id']) ?></td>
                        <td><?= htmlspecialchars($venta['fecha']) ?></td>
                        <td><?= htmlspecialchars($venta['producto']) ?></td>
                        <td><?= htmlspecialchars($venta['cantidad']) ?></td>
                        <td>$<?= number_format($venta['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulario para agregar una nueva venta -->
        <h3 class="text-center mt-4">Registrar Nueva Venta</h3>
        <form action="ventas.php" method="POST">
            <div class="mb-3">
                <label for="producto_id" class="form-label">Producto</label>
                <select class="form-select" id="producto_id" name="producto_id" required>
                    <?php
                    // Obtener productos disponibles
                    $stmt = $conn->query("SELECT id, nombre FROM productos");
                    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($productos as $producto): ?>
                        <option value="<?= $producto['id'] ?>"><?= $producto['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" class="form-control" id="total" name="total" step="0.01" min="0" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Registrar Venta</button>
        </form>

    </div>

    <?php
    // Manejar el envío de la venta
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $producto_id = intval($_POST['producto_id']);
        $cantidad = intval($_POST['cantidad']);
        $total = floatval($_POST['total']);

        // Insertar venta en la base de datos
        $sql = "INSERT INTO ventas (producto_id, cantidad, total, fecha) 
                VALUES (:producto_id, :cantidad, :total, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':producto_id', $producto_id);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':total', $total);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Venta registrada exitosamente</div>";
            // Redirigir para evitar resubmit al actualizar
            header("Location: ventas.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error al registrar la venta</div>";
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
