<?php
// Incluir archivo de conexión a la base de datos
include 'db.php';

// Obtener el ID del producto a editar
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Obtener el producto desde la base de datos
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Si el producto no existe
if (!$producto) {
    header("Location: index.php");
    exit();
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre = trim($_POST['nombre']);
    $categoria = trim($_POST['categoria']);
    $talle = trim($_POST['talle']);
    $color = trim($_POST['color']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $imagen = $producto['imagen']; // Mantener la imagen actual por defecto

    // Validar los campos obligatorios
    if ($nombre && $categoria && $talle && $color && $precio > 0 && $stock >= 0) {
        // Verificar si se cargó una nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenTmp = $_FILES['imagen']['tmp_name'];
            $imagenNombre = $_FILES['imagen']['name'];
            $imagenExtension = pathinfo($imagenNombre, PATHINFO_EXTENSION);
            $imagenRuta = 'images/' . uniqid() . '.' . $imagenExtension;

            // Mover la nueva imagen a la carpeta "images/"
            if (move_uploaded_file($imagenTmp, $imagenRuta)) {
                // Eliminar la imagen anterior si existe
                if ($producto['imagen'] !== 'images/default-image.jpg' && file_exists($producto['imagen'])) {
                    unlink($producto['imagen']);
                }
                $imagen = $imagenRuta; // Actualizar la ruta de la imagen
            } else {
                $error = "Error al mover la imagen al servidor.";
            }
        }

        try {
            // Preparar la actualización en la base de datos
            $sql = "UPDATE productos 
                    SET nombre = :nombre, categoria = :categoria, talle = :talle, color = :color, precio = :precio, stock = :stock, imagen = :imagen
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':talle', $talle);
            $stmt->bindParam(':color', $color);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                // Redirigir al índice con éxito
                header("Location: index.php?mensaje=Producto editado exitosamente");
                exit();
            } else {
                $error = "Error al editar el producto.";
            }
        } catch (PDOException $e) {
            $error = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        $error = "Todos los campos son obligatorios y deben ser válidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo para que todo esté centrado */
        body, html {
            height: 100%;
            margin: 0;
            display:contents;
            justify-content: center;
            align-items: center;
            background-color: black; /* Fondo oscuro */
        }

        .container {
            background-color: #f8f9fa; /* Fondo claro */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-top: 20px; /* Espacio desde arriba */
        }

        .form-control, .btn {
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            color: #212529;
            margin-bottom: 20px;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
    <title>Editar Producto</title>
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">
    <div class="container">
    <div class="bg-light p-4 rounded" style="width: 40rem;">
        <h2 class="text-center mb-4">Editar Producto</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="editar.php?id=<?= $producto['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="<?= htmlspecialchars($producto['categoria']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="talle" class="form-label">Talle</label>
                <input type="text" class="form-control" id="talle" name="talle" value="<?= htmlspecialchars($producto['talle']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control" id="color" name="color" value="<?= htmlspecialchars($producto['color']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" value="<?= htmlspecialchars($producto['precio']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" min="0" value="<?= htmlspecialchars($producto['stock']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Producto (opcional)</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                <div class="mt-2">
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen del producto" style="max-width: 100px;">
                </div>
            </div>
            <button type="submit" class="btn btn-danger w-100">Actualizar Producto</button>
        </form>
    </div>
    
</body>
</html>
