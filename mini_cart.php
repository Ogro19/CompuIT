<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tiendaCSS/mini_cart.css">

</head>
<body>
    
</body>
</html>
<?php
session_start();
include 'conexion.php';

$totalCompra = 0; // Variable para el total de la compra

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Obtener los detalles del producto
        $sql = "SELECT nombre, precio, imagen FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $subtotal = $product['precio'] * $quantity; // Subtotal por producto
            $totalCompra += $subtotal; // Sumar al total de la compra

            echo "<div class='mini-cart-item'>";
            echo "<img src='" . htmlspecialchars($product['imagen']) . "' alt='Imagen de " . htmlspecialchars($product['nombre']) . "' class='mini-cart-image'>";
            echo "<div class='mini-cart-details'>";
            echo "<p class='mini-cart-name'>" . htmlspecialchars($product['nombre']) . "</p>";
            echo "<p class='mini-cart-quantity'>Cantidad: $quantity</p>";
            echo "<p class='mini-cart-price'>Precio unitario: $" . number_format($product['precio'], 2) . "</p>";
            echo "<p class='mini-cart-subtotal'>Subtotal: $" . number_format($subtotal, 2) . "</p>";
            echo "</div>";
            echo "</div>";
        }
    }
    // Mostrar el total de la compra
    echo "<div class='mini-cart-total'>";
    echo "<p><strong>Total de la compra:</strong> $" . number_format($totalCompra, 2) . "</p>";
    echo "</div>";
} else {
    echo "<p>Tu carrito está vacío.</p>";
}
?>
