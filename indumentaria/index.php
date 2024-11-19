<?php 
require_once 'db.php';
require_once 'productos.php';
$productos = obtenerProductos($conn);
?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Gestión de Productos</title>
        
</head>
<body>
    <!-- Barra de navegación -->
     
    <nav class="menu">
        <div class="menu-logo">
            <a href="index.php">Sistema de Gestión de Productos</a>
        </div>
        <button class="navbar-toggler" type="button"
    data-bs-toggle="collapse"
    data-bs-target="#menu"
    aria-controls="navbarSupportedContent" aria-expanded="false"
    aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="menu"></div>
        <ul class="menu-links" id="menu-links">
            <li><a href="agregar.php">Agregar</a></li>
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="reportes.php">Reportes</a></li>
            <li><a href="cerrarsesion.php">Salir</a></li>
        </ul>
    </nav>

    <!-- Contenedor para las Cards de productos -->
    <div class="product-cards-container">
    <?php foreach ($productos as $producto): ?>
        <div class="product-card">
        <img src="<?= !empty($producto['imagen']) ? $producto['imagen'] 
        : 'images/default-image.jpg' ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="img-fluid">
        <div class="product-card-body">
                <h5><?= htmlspecialchars($producto['nombre']) ?></h5>
                <p><strong>Categoría:</strong> <?= htmlspecialchars($producto['categoria']) ?></p>
                <p><strong>Talle:</strong> <?= htmlspecialchars($producto['talle']) ?></p>
                <p><strong>Color:</strong> <?= htmlspecialchars($producto['color']) ?></p>
                <p><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>
                <p><strong>Stock:</strong> <?= $producto['stock'] ?> unidades</p>
                <a href="editar.php?id=<?= $producto['id'] ?>" class="btn-custom">Editar</a>
                <form action="eliminar.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                    <button type="submit" class="btn-custom" style="background-color: red;">Eliminar</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuToggle = document.getElementById("menu-toggle");
            const menuLinks = document.getElementById("menu-links");

            menuToggle.addEventListener("click", function () {
                menuLinks.classList.toggle("show");
            });
        });
    </script>
</body>
</html>
