<?php
require_once 'db.php';

// Obtener todos los productos
function obtenerProductos($conn) {
    $query = "SELECT * FROM productos";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Agregar un producto
function agregarProducto($conn, $datos) {
    $query = "INSERT INTO productos (nombre, categoria, talle, color, precio, stock) VALUES (:nombre, :categoria, :talle, :color, :precio, :stock)";
    $stmt = $conn->prepare($query);
    $stmt->execute($datos);
}

// Actualizar un producto
function actualizarProducto($conn, $datos) {
    $query = "UPDATE productos SET nombre = :nombre, categoria = :categoria, talle = :talle, color = :color, precio = :precio, stock = :stock WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute($datos);
}

// Eliminar un producto
function eliminarProducto($conn, $id) {
    $query = "DELETE FROM productos WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id' => $id]);
}
function obtenerVentas($conn) {
    try {
        // Consulta SQL para obtener las ventas
        $query = "SELECT 
                    ventas.id AS venta_id,
                    ventas.fecha AS fecha_venta,
                    clientes.nombre AS nombre_cliente,
                    productos.nombre AS nombre_producto,
                    detalle_ventas.cantidad AS cantidad,
                    detalle_ventas.precio_unitario AS precio_unitario,
                    (detalle_ventas.cantidad * detalle_ventas.precio_unitario) AS total
                  FROM ventas
                  INNER JOIN clientes ON ventas.cliente_id = clientes.id
                  INNER JOIN detalle_ventas ON ventas.id = detalle_ventas.venta_id
                  INNER JOIN productos ON detalle_ventas.producto_id = productos.id
                  ORDER BY ventas.fecha DESC";

        // Ejecutar la consulta
        $stmt = $conn->prepare($query);
        $stmt->execute();

        // Retornar los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener las ventas: " . $e->getMessage();
        return [];
    }
}

// Llamada a la función para obtener ventas y mostrarlas
$ventas = obtenerVentas($conn);

foreach ($ventas as $venta) {
    echo "Venta ID: " . $venta['venta_id'] . "<br>";
    echo "Fecha: " . $venta['fecha_venta'] . "<br>";
    echo "Cliente: " . $venta['nombre_cliente'] . "<br>";
    echo "Producto: " . $venta['nombre_producto'] . "<br>";
    echo "Cantidad: " . $venta['cantidad'] . "<br>";
    echo "Precio Unitario: " . $venta['precio_unitario'] . "<br>";
    echo "Total: " . $venta['total'] . "<br><br>";
}
?>