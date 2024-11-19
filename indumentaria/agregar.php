<?php 
// Incluir archivo de conexión a la base de datos
include 'db.php';

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre = trim($_POST['nombre']);
    $categoria = trim($_POST['categoria']);
    $talle = trim($_POST['talle']);
    $color = trim($_POST['color']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $imagen = '';

    // Verificar si se cargó una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = $_FILES['imagen']['name'];
        $imagenExtension = pathinfo($imagenNombre, PATHINFO_EXTENSION);
        $imagenRuta = 'images/' . uniqid() . '.' . $imagenExtension;

        // Mover la imagen al servidor
        if (move_uploaded_file($imagenTmp, $imagenRuta)) {
            $imagen = $imagenRuta;
        } else {
            $error = "Error al mover la imagen al servidor.";
        }
    }

    // Validar los campos obligatorios
    if ($nombre && $categoria && $talle && $color && $precio > 0 && $stock >= 0) {
        try {
            // Preparar e insertar en la base de datos
            $sql = "INSERT INTO productos (nombre, categoria, talle, color, precio, stock, imagen) 
                    VALUES (:nombre, :categoria, :talle, :color, :precio, :stock, :imagen)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':talle', $talle);
            $stmt->bindParam(':color', $color);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':imagen', $imagen);

            if ($stmt->execute()) {
                // Redirigir al índice con éxito
                header("Location: index.php?mensaje=Producto agregado exitosamente");
                exit();
            } else {
                $error = "Error al agregar el producto.";
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
    <title>Agregar Producto</title>
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
</head>
<body>
    <div class="container">
        <h2>Agregar Producto</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

            <div class="mb-1">
            <form action="guardar_producto.php" method="POST" enctype="multipart/form-data">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-1">
                <label for="categoria" class="form-label">Categoría</label>
                <input type="text" class="form-control" id="categoria" name="categoria" required>
            </div>
            <div class="mb-1">
                <label for="talle" class="form-label">Talle</label>
                <input type="text" class="form-control" id="talle" name="talle" required>
            </div>
            <div class="mb-1">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control" id="color" name="color" required>
            </div>
            <div class="mb-1">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
            </div>
            <div class="mb-1">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" min="0" required>
            </div>
            <div class="mb-1">
                <label for="imagen" class="form-label">Imagen del Producto</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
            </div>
            <button type="submit" class="btn btn-danger w-100">Agregar Producto</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
