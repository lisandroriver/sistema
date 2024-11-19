<?php
require_once 'db.php';
require_once 'productos.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$productos = obtenerProductos($conn);
$ventas = obtenerVentas($conn);
?>
<h1>Bienvenido, <?= $_SESSION['usuario_nombre'] ?></h1>
<a href="logout.php">Cerrar Sesión</a>
<h2>Productos</h2>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Stock</th>
            <th>Categoría</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['nombre'] ?></td>
                <td><?= $producto['stock'] ?></td>
                <td><?= $producto['categoria'] ?></td>
                <td>
                    <a href="editar.php?id=<?= $producto['id'] ?>">Editar</a>
                    <a href="eliminar.php?id=<?= $producto['id'] ?>">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Ventas Recientes</h2>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Total</th>
            <th>Método de Pago</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?= $venta['fecha'] ?></td>
                <td><?= $venta['total'] ?></td>
                <td><?= $venta['metodo_pago'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
