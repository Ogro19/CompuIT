<?php
session_start();
include 'conexion.php';

$items = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $sql = "SELECT nombre FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $items[] = [
                'nombre' => $row['nombre'],
                'cantidad' => $quantity
            ];
        }
    }
}

echo json_encode($items);
?>
