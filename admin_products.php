<?php
// Incluir el controlador y la conexión
include 'econtroler.php'; 
include 'conexion.php'; 

// Definir el umbral de stock bajo
$low_stock_threshold = 5;

// Verificar si se ha enviado un término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modificar la consulta SQL para incluir la búsqueda si se ha enviado un término
if ($searchTerm) {
    $sqlProductos = "SELECT * FROM productos WHERE nombre LIKE ?";
    $stmt = $conn->prepare($sqlProductos);
    $likeTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $likeTerm);
} else {
    $sqlProductos = "SELECT * FROM productos";
    $stmt = $conn->prepare($sqlProductos);
}

$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="tiendaCSS/admin_products.css">
</head>
<body>

    <!-- Incluir el header -->
    <?php include 'includes/header.php'; ?> 

    <!-- Incluir el sidebar -->
    <?php include 'includes/sidebar.php'; ?> 

    <div class="main-content">
        <h1>Gestión de Productos</h1>

        <!-- Leyenda bajo el título -->
        <p class="admin-welcome">
            Bienvenido al gestor de productos y servicios administrador. Aquí podrás añadir el producto o servicio que quieras y este se reflejará directamente hacia el apartado de ecommerce.
        </p>
        <img src="img/en-stock.png" alt="Stock">
        <li><a href="notificaciones_stock_bajo.php">Stock </a></li>
        
        <a href="admin_add_product.php" class="btn-add-product">Agregar Producto</a>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="admin_products.php" class="search-form">
            <input type="text" name="search" placeholder="Buscar producto por nombre..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Buscar</button>
        </form>

        <!-- Mostrar productos existentes -->
        <?php if (!empty($products)): ?>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th> <!-- Nueva columna para mostrar stock -->
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($product['descripcion']); ?></td>
                            <td>$<?php echo number_format($product['precio'], 2); ?></td>
                            <td <?php if ($product['stock'] <= $low_stock_threshold) echo 'style="color: red;"'; ?>>
                                <?php echo htmlspecialchars($product['stock']); ?>
                            </td>
                            <td><img src="<?php echo htmlspecialchars($product['imagen']); ?>" alt="Imagen del producto" width="50"></td>
                            <td>
                                <a href="admin_edit_product.php?id=<?php echo $product['id']; ?>" class="product-link">Editar</a> |
                                <a href="admin_delete_product.php?id=<?php echo $product['id']; ?>" class="product-link" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </div>
</body>
</html>
