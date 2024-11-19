<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que se recibió el ID
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];

        try {
            // Consulta SQL para eliminar
            $sql = "DELETE FROM productos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Redirigir al listado con éxito
                header("Location: index.php?mensaje=Producto eliminado correctamente");
                exit();
            } else {
                // Mostrar mensaje de error
                header("Location: index.php?error=No se pudo eliminar el producto");
                exit();
            }
        } catch (PDOException $e) {
            die("Error al eliminar el producto: " . $e->getMessage());
        }
    } else {
        // ID no válido
        header("Location: index.php?error=ID no válido");
        exit();
    }
} else {
    // Método no permitido
    header("Location: index.php");
    exit();
}
?>
