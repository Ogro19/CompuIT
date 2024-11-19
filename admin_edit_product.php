<?php  
// Incluir la conexión y el controlador
include 'conexion.php';
include 'econtroler.php';

// Verificar si se ha pasado el ID del producto
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Obtener los datos del producto actual
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si el producto existe
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }

    // Procesar la actualización del producto si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $imagen = $product['imagen']; // Mantener la imagen actual si no se cambia

        // Comprobar si se sube una nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $imageType = $_FILES['imagen']['type'];
            $imageSize = $_FILES['imagen']['size'];

            if (in_array($imageType, $allowedTypes) && $imageSize <= 500000) {
                $imageName = uniqid() . '_' . basename($_FILES['imagen']['name']);
                $imageTmpName = $_FILES['imagen']['tmp_name'];
                $imageFolder = 'uploads/' . $imageName;

                if (move_uploaded_file($imageTmpName, $imageFolder)) {
                    $imagen = $imageFolder;
                }
            }
        }

        // Actualizar el producto en la base de datos
        $sqlUpdate = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $imagen, $productId);

        if ($stmtUpdate->execute()) {
            echo "<script>alert('Producto actualizado correctamente.'); window.location.href='admin_products.php';</script>";
        } else {
            echo "Error al actualizar el producto: " . $conn->error;
        }
    }

} else {
    echo "ID de producto no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="tiendaCSS/editar.css"> <!-- Asegúrate de que esta hoja de estilo contenga los mismos estilos que en las otras páginas -->
</head>
<body>

    <!-- Incluir header y sidebar -->
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <!-- Contenedor principal que incluye la sidebar y el contenido -->
    <div class="main-content">
        <div class="form-container">
            <h1>Editar Producto</h1>

            <form action="" method="POST" enctype="multipart/form-data">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($product['nombre']); ?>" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($product['descripcion']); ?></textarea>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($product['precio']); ?>" required>

                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>

                <label for="imagen">Imagen del Producto:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <p>Imagen actual:</p>
                <img src="<?php echo htmlspecialchars($product['imagen']); ?>" alt="Imagen actual del producto" width="100">

                <button type="submit">Actualizar Producto</button>
            </form>

            <a href="admin_products.php" class="back-link">Volver a Gestión de Productos</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
