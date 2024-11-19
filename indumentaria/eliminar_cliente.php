<?php
// Incluir archivo de conexión a la base de datos
include 'db.php';

// Verificar si se ha recibido un parámetro "id" a través de POST o GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Asegúrate de convertir el ID a un número entero

    try {
        // Preparar la consulta SQL para eliminar el cliente
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página de clientes con un mensaje de éxito
            header("Location: clientes.php?mensaje=Cliente eliminado exitosamente");
            exit();
        } else {
            // Si hay un error, redirigir con un mensaje de error
            header("Location: clientes.php?error=Error al eliminar el cliente");
            exit();
        }
    } catch (PDOException $e) {
        // Si ocurre una excepción, redirigir con un mensaje de error
        header("Location: clientes.php?error=Error en la base de datos: " . $e->getMessage());
        exit();
    }
} else {
    // Si no se recibe el ID, redirigir con un mensaje de error
    header("Location: clientes.php?error=ID no válido");
    exit();
}
?>
