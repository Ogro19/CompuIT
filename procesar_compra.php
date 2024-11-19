<?php
session_start();
include 'conexion.php';

// Verificar si el carrito está vacío
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: ecommerce.php");
    exit();
}

// Verificar si el usuario está logueado y obtener el ID del usuario
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Obtener la dirección del usuario desde la base de datos
$sql = "SELECT direccion, ciudad, codigo_postal FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $direccion = $user_data['direccion'];
    $ciudad = $user_data['ciudad'];
    $codigo_postal = $user_data['codigo_postal'];
} else {
    header("Location: perfil.php");
    exit();
}

// Obtener el método de pago seleccionado y el total
$metodo_pago = $_POST['metodo_pago'] ?? 'sin especificar';
$total = $_POST['total'] ?? 0.00; // Valor predeterminado si no se envía el total

// Insertar el pedido en la tabla `orders`
$stmt = $conn->prepare("INSERT INTO orders (user_id, total, status, payment_method, direccion, ciudad, codigo_postal) VALUES (?, ?, 'pendiente', ?, ?, ?, ?)");
$stmt->bind_param("idssss", $user_id, $total, $metodo_pago, $direccion, $ciudad, $codigo_postal);
$stmt->execute();
$order_id = $stmt->insert_id; // Obtener el ID del pedido recién creado

// Umbral para notificación de stock bajo
$low_stock_threshold = 5;

// Insertar cada producto del carrito en la tabla `order_items` y actualizar el inventario
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Obtener el precio, stock actual y nombre del producto desde la base de datos
    $sql = "SELECT precio, stock, nombre FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $price = $product['precio'];
        $current_stock = $product['stock'];
        $product_name = $product['nombre'];

        // Verificar si hay suficiente inventario
        if ($current_stock >= $quantity) {
            // Insertar el producto en `order_items`
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt->execute();

            // Actualizar el inventario del producto
            $new_stock = $current_stock - $quantity;
            $update_stock = $conn->prepare("UPDATE productos SET stock = ? WHERE id = ?");
            $update_stock->bind_param("ii", $new_stock, $product_id);
            $update_stock->execute();

            // Verificar si el nuevo inventario es igual al umbral de stock bajo
            if ($new_stock == $low_stock_threshold) {
                // Notificación de stock bajo (opcional: enviar por correo al administrador)
                $admin_email = "admin@tuempresa.com";
                $subject = "Notificación de Stock Bajo";
                $message = "El producto '$product_name' tiene un inventario bajo. Solo quedan $new_stock unidades.";
                mail($admin_email, $subject, $message);
            }
        } else {
            // Mostrar un mensaje de error si no hay suficiente inventario
            echo "No hay suficiente inventario para el producto " . htmlspecialchars($product_name);
            exit();
        }
    }
}

// Limpiar el carrito después de la compra
unset($_SESSION['cart']);

// Redirigir a una página de confirmación
header("Location: confirmacion.php");
exit();
?>
