<?php
// Incluir la conexión y el controlador
include 'conexion.php';
include 'econtroler.php';

// Verificar si se ha pasado el ID del producto
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Eliminar el producto de la base de datos
    $sqlDelete = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo "<script>alert('Producto eliminado correctamente.'); window.location.href='admin_products.php';</script>";
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }

} else {
    echo "ID de producto no proporcionado.";
    exit();
}
?>
