<?php
require_once 'db.php';

// Iniciar sesión
session_start();

// Validar usuario
function autenticar($email, $password, $conn) {
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        return true;
    }
    return false;
}

// Agregar venta
function registrarVenta($conn, $metodo_pago, $productos) {
    $conn->beginTransaction();
    try {
        $query = "INSERT INTO ventas (metodo_pago, total) VALUES (:metodo_pago, :total)";
        $stmt = $conn->prepare($query);
        $total = array_reduce($productos, fn($sum, $p) => $sum + $p['precio'] * $p['cantidad'], 0);
        $stmt->execute(['metodo_pago' => $metodo_pago, 'total' => $total]);
        $venta_id = $conn->lastInsertId();

        foreach ($productos as $producto) {
            $query_detalle = "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, subtotal) VALUES (:venta_id, :producto_id, :cantidad, :subtotal)";
            $stmt_detalle = $conn->prepare($query_detalle);
            $subtotal = $producto['precio'] * $producto['cantidad'];
            $stmt_detalle->execute([
                'venta_id' => $venta_id,
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'subtotal' => $subtotal
            ]);

            $query_stock = "UPDATE productos SET stock = stock - :cantidad WHERE id = :id";
            $stmt_stock = $conn->prepare($query_stock);
            $stmt_stock->execute(['cantidad' => $producto['cantidad'], 'id' => $producto['id']]);
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        return false;
    }
}
?>
