<?php
// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir la conexión a la base de datos
include 'conexion.php';

// Redirigir al carrito si está vacío
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: ecommerce.php");
    exit();
}

// Calcular el total de la compra
$totalCompra = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $sql = "SELECT nombre, precio FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $subtotal = $product['precio'] * $quantity;
        $totalCompra += $subtotal;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="tiendaCSS/checkout.css">
</head>
<body>
    <h1>Checkout</h1>
    <form action="procesar_compra.php" method="POST">
        <!-- Información de Envío -->
        <h2>Información de Envío</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>
        
        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" required>
        
        <label for="codigo_postal">Código Postal:</label>
        <input type="text" id="codigo_postal" name="codigo_postal" required>
        
        <!-- Resumen del pedido -->
        <h2>Resumen del Pedido</h2>
        <div class="resumen-pedido">
            <?php
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $sql = "SELECT nombre, precio FROM productos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    echo "<p>" . htmlspecialchars($product['nombre']) . " x " . $quantity . " - $" . number_format($product['precio'] * $quantity, 2) . "</p>";
                }
            }
            ?>
            <p><strong>Total: $<?php echo number_format($totalCompra, 2); ?></strong></p>
        </div>

        <!-- Selección de método de pago -->
        <h2>Método de Pago</h2>
        <label><input type="radio" name="metodo_pago" value="tarjeta" required> Tarjeta de Crédito</label><br>
        <label><input type="radio" name="metodo_pago" value="paypal" required> PayPal</label><br>

        <button type="submit">Completar Compra</button>
    </form>
</body>
</html>
