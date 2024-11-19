<?php
session_start();

// Incluir la conexión a la base de datos y el controlador
include 'conexion.php';

// Capturar los datos del producto
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Agregar el producto al carrito o actualizar la cantidad
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }

    // Devolver la cantidad total de productos en el carrito como JSON
    echo json_encode(['total_items' => array_sum($_SESSION['cart'])]);
}
?>
