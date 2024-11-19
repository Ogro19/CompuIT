<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Umbral de stock bajo
$low_stock_threshold = 5;

// Actualizar el stock si se envió un ajuste
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $new_stock = $_POST['new_stock']; // Stock ingresado manualmente por el admin

    // Obtener el nombre y el stock actual del producto para la notificación
    $sql = "SELECT nombre, stock FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product && isset($product['stock'])) {  // Verifica si el producto existe y tiene stock definido
        // Actualizar el stock en la base de datos
        $sql = "UPDATE productos SET stock = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_stock, $product_id);
        $stmt->execute();

        // Mostrar alerta solo si el nuevo stock es igual al umbral
        if ($new_stock == $low_stock_threshold) {
            echo "<script>alert('Advertencia: El stock del producto \"{$product['nombre']}\" ha llegado a $low_stock_threshold unidades.');</script>";
        }
    } else {
        echo "<script>alert('Error: El producto no existe o el stock no está definido.');</script>";
    }
}

// Obtener productos con stock bajo
$sql = "SELECT id, nombre, stock FROM productos WHERE stock <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $low_stock_threshold);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones de Stock Bajo</title>
    <link rel="stylesheet" href="tiendaCSS/stock.css">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?> 
</head>
<body>
    <div class="main-content">
        <h1>Productos con Stock Bajo</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Ajustar Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <tr class="<?php echo $product['stock'] <= $low_stock_threshold ? 'low-stock' : ''; ?>">
                            <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="new_stock" value="<?php echo $product['stock']; ?>" min="0">
                                    <button type="submit" name="action" value="update">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay productos con stock bajo.</p>
        <?php endif; ?>
        
        <!-- Botón para regresar a la página de gestión de productos -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="admin_products.php" class="btn-back">Volver a Gestión de Productos</a>
        </div>
    </div>
</body>
</html>
