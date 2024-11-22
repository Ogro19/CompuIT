<?php
session_start();
include 'conexion.php';

// Funciones para el manejo del carrito
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {   
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function updateCart($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function getCartItems() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

// Lógica para añadir, eliminar o actualizar productos usando POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if (is_numeric($quantity) && $quantity > 0) {
            addToCart($product_id, $quantity);
        }
    }

    if (isset($_POST['remove_from_cart'])) {
        removeFromCart($_POST['product_id']);
    }

    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if (is_numeric($quantity) && $quantity > 0) {
            updateCart($product_id, $quantity);
        }
    }
}

// Obtener los productos del carrito
$cartItems = getCartItems();
$cartProducts = [];
$total = 0;

if (!empty($cartItems)) {
    foreach ($cartItems as $product_id => $quantity) {
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $product['quantity'] = $quantity;
            $product['subtotal'] = $product['precio'] * $quantity;
            $cartProducts[] = $product;
            $total += $product['subtotal'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
    <link rel="stylesheet" href="tiendaCSS/carrito.css">
    <script>
        function confirmDelete(productId) {
            if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                document.getElementById('remove-form-' + productId).submit();
            }
        }

        function updateQuantity(productId) {
            let quantity = document.getElementById('quantity-' + productId).value;
            document.getElementById('hidden-quantity-' + productId).value = quantity;
        }
    </script>
</head>
<body>

    <!-- Incluir el header -->
    <?php include 'includes/header.php'; ?>

    <!-- Incluir el sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Tu carrito de compras</h1>

        <?php if (!empty($cartProducts)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartProducts as $product): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($product['imagen']); ?>" alt="Imagen del producto" style="width: 100px; height: 100px;"></td>
                            <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                            <td>
                                <form id="update-form-<?php echo $product['id']; ?>" action="ecart.php" method="POST">
                                    <input type="number" id="quantity-<?php echo $product['id']; ?>" name="quantity" value="<?php echo $product['quantity']; ?>" min="1" oninput="updateQuantity(<?php echo $product['id']; ?>)">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" id="hidden-quantity-<?php echo $product['id']; ?>" name="quantity" value="<?php echo $product['quantity']; ?>">
                                    <button type="submit" name="update_quantity">Actualizar</button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($product['precio'], 2); ?></td>
                            <td>$<?php echo number_format($product['subtotal'], 2); ?></td>
                            <td>
                                <form id="remove-form-<?php echo $product['id']; ?>" action="ecart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" name="remove_from_cart">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total a pagar: $<?php echo number_format($total, 2); ?></h3>

            <!-- Formulario para seleccionar método de pago y proceder a compra -->
            <h2>Selecciona tu método de pago</h2>
            <form action="procesar_compra.php" method="POST">
                <label for="metodo_pago">Método de pago:</label>
                <select name="metodo_pago" id="metodo_pago" required>
                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                    <option value="paypal">PayPal</option>
                    <option value="transferencia">Transferencia Bancaria</option>
                </select>
                
                <input type="hidden" name="total" value="<?php echo $total; ?>">
                <button type="submit" class="purchase-button">Confirmar compra</button>
            </form>
        <?php else: ?>
            <p>Tu carrito está vacío.</p>
        <?php endif; ?>

        <!-- Botón para regresar a la tienda -->
        <div class="back-to-store">
            <a href="ecommerce.php" class="back-button">Seguir comprando</a>
        </div>
    </div>

    <!-- Incluir el pie de página -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>
