<?php
include 'db.php';

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $talle = $_POST['talle'];
    $color = $_POST['color'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Subir la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Obtener información del archivo subido
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = $_FILES['imagen']['name'];
        $imagenExtension = pathinfo($imagenNombre, PATHINFO_EXTENSION);
        $imagenRuta = 'images/' . uniqid() . '.' . $imagenExtension;

        // Mover la imagen a la carpeta "images/"
        if (move_uploaded_file($imagenTmp, $imagenRuta)) {
            // Insertar el producto en la base de datos con la ruta de la imagen
            $sql = "INSERT INTO productos (nombre, categoria, talle, color, precio, stock, imagen) VALUES (:nombre, :categoria, :talle, :color, :precio, :stock, :imagen)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':talle', $talle);
            $stmt->bindParam(':color', $color);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':imagen', $imagenRuta);

            if ($stmt->execute()) {
                echo "Producto agregado exitosamente.";
                header('Location: index.php'); // Redirige a la página principal después de agregar
            } else {
                echo "Error al guardar el producto.";
            }
        } else {
            echo "Error al mover la imagen al servidor.";
        }
    } else {
        echo "Por favor, selecciona una imagen.";
    }
}
?>
