<?php
// Incluir conexión a la base de datos y controlador
include 'econtroler.php'; 
include 'conexion.php';

// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) { 
    session_start();
}

// Comprobar si la dirección de entrega está guardada en la sesión
$direccion_entrega = isset($_SESSION['direccion_entrega']) ? $_SESSION['direccion_entrega'] : 'Actualizar ubicación';

// Capturar el término de búsqueda, si existe
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Capturar el número de página actual, si existe (por defecto, es la primera página)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12; // Número de productos por página

// Obtener los productos y el total de páginas
$products = getProducts($searchTerm, $limit, $page);
$totalPages = getTotalPages($searchTerm, $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce</title>
    <link rel="stylesheet" href="tiendaCSS/ecommerce.css"> <!-- Enlace al CSS de ecommerce -->
</head>
<body>
    <!-- Incluir el header -->
    <?php include 'includes/Theader.php'; ?> 
    <?php include 'includes/sidebar.php'; ?> 

    <!-- Mostrar la dirección de entrega debajo del header -->
    <div class="address-display">
        <p>Dirección de entrega: 
            <?php echo htmlspecialchars($direccion_entrega); ?>
        </p>
    </div>

    <h1>Tienda en línea</h1>

    <!-- Botón de regreso al ecommerce principal si hay una búsqueda -->
    <?php if (!empty($searchTerm)): ?>
        <div style="text-align: center; margin: 20px 0;">
            <a href="ecommerce.php" class="back-button">Volver a la tienda principal</a>
        </div>
    <?php endif; ?>

    <!-- Sección de productos -->
    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="producto_detalle.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo htmlspecialchars($product['imagen']); ?>" alt="Imagen del producto" class="product-image">
                    </a>
                    <h3><a href="producto_detalle.php?id=<?php echo $product['id']; ?>" class="product-name-link"><?php echo htmlspecialchars($product['nombre']); ?></a></h3>
                    <p><?php echo htmlspecialchars($product['descripcion']); ?></p>
                    <p>Precio: $<?php echo number_format($product['precio'], 2); ?></p>

                    <!-- Enlace para editar el producto (solo para administradores) -->
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="admin_edit_product.php?id=<?php echo $product['id']; ?>" class="edit-link">Editar Producto</a>
                    <?php endif; ?>

                    <form method="post" action="ecart.php"> <!-- Redirigir al carrito -->
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <label for="quantity">Cantidad:</label>
                        <input type="number" name="quantity" min="1" value="1" required>
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn">Agregar al carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?php echo urlencode($searchTerm); ?>&page=<?php echo $i; ?>" 
                   class="<?php echo $i === $page ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>

    <!-- Incluir el pie de página común -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
