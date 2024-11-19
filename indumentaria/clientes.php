<?php
// Incluir archivo de conexión a la base de datos
include 'db.php';

// Obtener todos los clientes de la base de datos
$sql = "SELECT * FROM clientes ORDER BY nombre ASC"; // O cualquier orden que prefieras
$stmt = $conn->query($sql);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Clientes</title>
</head>
<body >
    <!-- Barra de navegación -->
    <nav class="menu">
        <div class="menu-logo">
            <a href="index.php">Sistema de Gestión de Productos</a>
        </div>
        <ul class="menu-links" id="menu-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="agregar_clientes.php">Añadir Cliente</a></li>
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="reportes.php">Reportes</a></li>
            <li><a href="cerrarsesion.php">Salir</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Clientes Registrados</h2>

        <!-- Botón para agregar nuevo cliente -->

        <!-- Tabla de clientes -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                        <td><?= htmlspecialchars($cliente['email']) ?></td>
                        <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                        <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                        <td>
                       
                            <!-- Enlace para editar cliente -->
                            <form action="editar_cliente.php" method="GET" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                <button type="submit" class="btn btn-danger">Editar</button>
                            </form>
                            <!-- Formulario para eliminar cliente -->
                            <form action="eliminar_cliente.php" method="GET" style="display:inline;" 
                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
                                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
