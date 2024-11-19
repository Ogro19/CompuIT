<?php 
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($conn, $_POST['precio']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']); // Nuevo campo de stock
    $imagen = '';  // Inicializar variable de imagen

    // Manejar la subida de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $imageType = $_FILES['imagen']['type'];
        $imageSize = $_FILES['imagen']['size'];

        if (in_array($imageType, $allowedTypes) && $imageSize <= 500000) { // Tamaño máximo 500KB
            $imageName = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $imageTmpName = $_FILES['imagen']['tmp_name'];
            $imageFolder = 'uploads/' . $imageName;

            if (move_uploaded_file($imageTmpName, $imageFolder)) {
                $imagen = $imageFolder;
            } else {
                echo "<p>Error al subir la imagen</p>";
            }
        } else {
            echo "<p>Solo se permiten archivos de imagen JPG, PNG o GIF con un tamaño máximo de 500KB.</p>";
        }
    }

    // Insertar el producto en la base de datos, incluyendo el stock
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$imagen')";

    if (mysqli_query($conn, $sql)) {
        echo "<p>Producto agregado exitosamente</p>";
    } else {
        echo "Error al agregar el producto: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="tiendaCSS/agregar.css">
</head>
<body>
    
    <!-- Incluir header y sidebar -->
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="form-container">
            <h1>Agregar Producto</h1>
            <form action="admin_add_product.php" method="POST" enctype="multipart/form-data">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" name="nombre" id="nombre" required>

                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required></textarea>

                <label for="precio">Precio:</label>
                <input type="number" name="precio" id="precio" step="0.01" required>

                <label for="stock">Stock:</label>
                <input type="number" name="stock" id="stock" required> <!-- Nuevo campo para el stock -->

                <label for="imagen">Imagen del Producto:</label>
                <input type="file" name="imagen" id="imagen" accept="image/*">

                <button type="submit">Agregar Producto</button>
            </form>

            <a href="admin_products.php" class="back-link">Volver a Gestión de Productos</a>
        </div>
    </div>

    <!-- Incluir el footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
