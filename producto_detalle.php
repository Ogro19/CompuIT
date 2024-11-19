<?php
// Incluir conexión a la base de datos y controlador
include 'econtroler.php'; 
include 'conexion.php';

session_start();

// Obtener el ID del producto desde la URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener los detalles del producto
$product = getProductById($product_id);

if (!$product) {
    // Redirigir a ecommerce.php si el producto no existe
    header("Location: ecommerce.php");
    exit();
}

// Verificar si el campo stock existe, si no, asignarle un valor predeterminado
$stockDisponible = isset($product['stock']) ? $product['stock'] : 'No disponible';

// Obtener las reseñas del producto y el nombre y foto del usuario que las hizo
$sql = "SELECT r.*, u.username AS usuario_nombre, u.profileimage AS usuario_foto 
        FROM reseñas AS r 
        JOIN usuarios AS u ON r.usuario_id = u.id 
        WHERE r.producto_id = ? 
        ORDER BY r.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$resenas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calcular la valoración promedio
$avgRating = 0;
if (count($resenas) > 0) {
    $totalRating = array_sum(array_column($resenas, 'valoracion'));
    $avgRating = round($totalRating / count($resenas), 1);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['nombre']); ?> - Detalle del Producto</title>
    <link rel="stylesheet" href="tiendaCSS/producto_detalle.css">
</head>
<body>
    <!-- Incluir el header -->
    <?php include 'includes/Theader.php'; ?> 
    <?php include 'includes/sidebar.php'; ?> 

    <!-- Detalles del producto -->
    <div class="product-details">
        <img src="<?php echo htmlspecialchars($product['imagen']); ?>" alt="Imagen del producto" class="product-detail-image">
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['nombre']); ?></h1>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($product['descripcion']); ?></p>
            <p><strong>Precio:</strong> $<?php echo number_format($product['precio'], 2); ?></p>
            <p><strong>Valoración promedio:</strong> <?php echo $avgRating; ?> / 5</p>
            <p><strong>Stock disponible:</strong> <?php echo htmlspecialchars($stockDisponible); ?></p>

            <!-- Formulario para agregar al carrito -->
            <form method="post" action="ecart.php">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <label for="quantity">Cantidad:</label>
                <input type="number" name="quantity" min="1" max="<?php echo is_numeric($stockDisponible) ? $stockDisponible : 1; ?>" value="1" required>
                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Agregar al carrito</button>
            </form>
        </div>
    </div>

    <!-- Sección de reseñas -->
    <div class="reviews-section">
        <h2>Reseñas de los usuarios</h2>
        <?php if (count($resenas) > 0): ?>
            <?php foreach ($resenas as $resena): ?>
                <div class="review">
                    <div class="review-header">
                        <img src="<?php echo htmlspecialchars($resena['usuario_foto']); ?>" alt="Foto de usuario" class="user-photo">
                        <p><strong><?php echo htmlspecialchars($resena['usuario_nombre']); ?></strong> - <?php echo $resena['fecha']; ?></p>
                    </div>
                    <p>Valoración: <?php echo str_repeat("★", $resena['valoracion']); ?> (<?php echo $resena['valoracion']; ?>)</p>
                    <p><?php echo htmlspecialchars($resena['reseña']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay reseñas para este producto.</p>
        <?php endif; ?>
    </div>

    <!-- Formulario para dejar una reseña (solo para usuarios registrados) -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="leave-review">
            <h2>Deja tu reseña</h2>
            <form action="guardar_resena.php" method="POST">
                <input type="hidden" name="producto_id" value="<?php echo $product_id; ?>">
                <label for="valoracion">Valoración:</label>
                <select name="valoracion" required>
                    <option value="5">5 - Excelente</option>
                    <option value="4">4 - Muy bueno</option>
                    <option value="3">3 - Bueno</option>
                    <option value="2">2 - Regular</option>
                    <option value="1">1 - Malo</option>
                </select>
                <label for="reseña">Reseña:</label>
                <textarea name="reseña" required></textarea>
                <button type="submit">Enviar reseña</button>
            </form>
        </div>
    <?php else: ?>
        <p><a href="login.php">Inicia sesión</a> para dejar una reseña.</p>
    <?php endif; ?>

    <!-- Incluir el pie de página común -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
