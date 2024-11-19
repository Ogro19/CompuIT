<?php
session_start();
include 'conexion.php';

// Verificar que el usuario esté logueado y que exista un order_id reciente en la sesión
if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_id'])) {
    header("Location: ecommerce.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_SESSION['order_id'];

// Obtener la información del pedido desde la base de datos
$sql_order = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("ii", $order_id, $user_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    echo "<p>Error: No se encontró el pedido.</p>";
    exit();
}

// Obtener los productos del pedido
$sql_items = "SELECT oi.quantity, oi.price, p.nombre 
              FROM order_items oi 
              JOIN productos p ON oi.product_id = p.id 
              WHERE oi.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$order_items = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="tiendaCSS/confirmacion.css">
</head>
<body>
    <div class="confirmation-container">
        <h1>¡Gracias por tu compra!</h1>
        <p>Pedido #<?php echo htmlspecialchars($order['id']); ?> confirmado el <?php echo $order['order_date']; ?></p>
        
        <div class="order-summary">
            <h2>Resumen del pedido</h2>
            <p><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>
            <p><strong>Estado:</strong> <?php echo ucfirst($order['status']); ?></p>
            <p><strong>Método de pago:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
            <h3>Detalles de envío:</h3>
            <p><?php echo htmlspecialchars($order['direccion']); ?></p>
            <p><?php echo htmlspecialchars($order['ciudad']); ?>, <?php echo htmlspecialchars($order['codigo_postal']); ?></p>
            
            <h3>Productos:</h3>
            <ul class="product-list">
                <?php while ($item = $order_items->fetch_assoc()): ?>
                    <li>
                        <?php echo htmlspecialchars($item['nombre']); ?> - 
                        Cantidad: <?php echo $item['quantity']; ?> - 
                        Precio unitario: $<?php echo number_format($item['price'], 2); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="confirmation-footer">
            <p>Hemos enviado los detalles de tu pedido a tu correo. Puedes ver el estado en <a href="historial_compras.php">Historial de Compras</a>.</p>
            <a href="ecommerce.php" class="continue-shopping-button">Volver a la tienda</a>
        </div>
    </div>
</body>
</html>

<?php
// Limpiar el order_id de la sesión para evitar duplicaciones en futuras confirmaciones
unset($_SESSION['order_id']);
?>
