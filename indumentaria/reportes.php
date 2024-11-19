<?php
// Incluir archivo de conexión a la base de datos
include 'db.php';

// Inicializar la variable de error
$error = '';

// Obtener el reporte de productos
try {
    $sql = "SELECT nombre, categoria, SUM(stock) AS total_stock FROM productos GROUP BY nombre, categoria ORDER BY nombre ASC";
    $stmt = $conn->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener los datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Reportes</title>
</head>
<body class="bg-dark text-dark">
    <!-- Barra de navegación -->
    <nav class="menu">
        <div class="menu-logo">
            <a href="index.php">Sistema de Gestión de Productos</a>
        </div>
        <ul class="menu-links" id="menu-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="reportes.php">Reportes</a></li>
            <li><a href="cerrarsesion.php">Salir</a></li>
        </ul>
    </nav>

    <div class="container mt-5">
        <h2>Reporte de Productos y Stock</h2>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Tabla de productos -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Stock Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['categoria']) ?></td>
                        <td><?= htmlspecialchars($producto['total_stock']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>
</html>
