<?php
// Incluir la conexión a la base de datos
include 'conexion.php';
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar que los datos requeridos estén presentes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'], $_POST['valoracion'], $_POST['reseña'])) {
    $producto_id = (int)$_POST['producto_id'];
    $usuario_id = $_SESSION['user_id'];
    $valoracion = (int)$_POST['valoracion'];
    $reseña = trim($_POST['reseña']);

    // Preparar e insertar la reseña en la base de datos
    $sql = "INSERT INTO reseñas (producto_id, usuario_id, valoracion, reseña) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $producto_id, $usuario_id, $valoracion, $reseña);

    if ($stmt->execute()) {
        // Redirigir de vuelta a la página de detalles del producto después de guardar
        header("Location: producto_detalle.php?id=" . $producto_id);
        exit();
    } else {
        echo "Error al guardar la reseña: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
