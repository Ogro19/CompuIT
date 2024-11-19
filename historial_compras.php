<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener todos los pedidos del usuario
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .order {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order h2 {
            color: #333;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .order p {
            margin: 5px 0;
        }
        .order ul {
            list-style-type: none;
            padding-left: 0;
        }
        .order ul li {
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }
        .order ul li span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Historial de Compras</h1>

    <?php if ($orders->num_rows > 0): ?>
        <?php while ($order = $orders->fetch_assoc()): ?>
            <div class="order">
                <h2>Pedido #<?php echo $order['id']; ?></h2>
                <p>Fecha: <?php echo $order['order_date']; ?></p>
                <p>Total: $<?php echo number_format($order['total'], 2); ?></p>
                <p>Estado: <?php echo ucfirst($order['status']); ?></p>

                <!-- Obtener los artículos de cada pedido -->
                <?php
                $order_id = $order['id'];
                $sql_items = "SELECT oi.quantity, oi.price, p.nombre 
                              FROM order_items oi
                              JOIN productos p ON oi.product_id = p.id
                              WHERE oi.order_id = ?";
                $stmt_items = $conn->prepare($sql_items);
                $stmt_items->bind_param("i", $order_id);
                $stmt_items->execute();
                $items = $stmt_items->get_result();
                ?>

                <ul>
                    <?php while ($item = $items->fetch_assoc()): ?>
                        <li>
                            <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                            <div>
                                Cantidad: <?php echo $item['quantity']; ?> &nbsp; | &nbsp; 
                                Precio: $<?php echo number_format($item['price'], 2); ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No has realizado compras aún.</p>
    <?php endif; ?>
</body>
</html>
