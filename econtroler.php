<?php 
include 'conexion.php';

function getProducts($searchQuery = '', $limit = 12, $page = 1) {
    global $conn;

    // Verificar si la conexión es válida
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Convertir el término de búsqueda a minúsculas para evitar problemas de capitalización
    $searchQuery = strtolower($searchQuery);
    $likePattern = '%' . $searchQuery . '%';
    $offset = ($page - 1) * $limit;

    // Consulta que prioriza el producto exacto y luego los similares, ignorando mayúsculas y minúsculas
    $sql = "SELECT * FROM productos WHERE LOWER(nombre) LIKE ? ORDER BY 
            CASE 
                WHEN LOWER(nombre) = ? THEN 1 
                WHEN LOWER(nombre) LIKE ? THEN 2 
                ELSE 3 
            END, nombre ASC 
            LIMIT ? OFFSET ?";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("sssii", $likePattern, $searchQuery, $likePattern, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validar si hay productos en la base de datos y obtener los resultados
    $products = [];
    if ($result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
    }

    // Cerrar el statement y devolver productos
    $stmt->close();
    return $products;
}

// Función para obtener el total de páginas basadas en el límite de productos por página
function getTotalPages($searchQuery = '', $limit = 12) {
    global $conn;

    // Verificar si la conexión es válida
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $searchQuery = strtolower($searchQuery);
    $likePattern = '%' . $searchQuery . '%';

    $sql = "SELECT COUNT(*) as total FROM productos WHERE LOWER(nombre) LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $totalProducts = $row['total'];
    return ceil($totalProducts / $limit);  // Redondear hacia arriba para obtener el total de páginas
}

// Función para obtener detalles de un solo producto
function getProductById($product_id) {
    global $conn;

    // Preparar la consulta de forma segura usando una declaración preparada
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $product_id);  // "i" indica que el parámetro es un entero
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener el resultado como un array asociativo
    $product = $result->fetch_assoc();

    // Cerrar el statement y devolver el producto
    $stmt->close();
    return $product;
}

// Función para procesar pedidos (ejemplo)
function processOrder($cartItems) {
    global $conn;

    // Verificar si la conexión es válida
    if (!$conn) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Procesar cada producto en el carrito
    foreach ($cartItems as $product_id => $quantity) {
        // Escapar los valores para prevenir inyecciones SQL
        $product_id = mysqli_real_escape_string($conn, $product_id);
        $quantity = mysqli_real_escape_string($conn, $quantity);

        // Inserción en tabla de "orders" (ajustar según el esquema de base de datos)
        $sql = "INSERT INTO orders (product_id, quantity, order_date) VALUES ('$product_id', '$quantity', NOW())";
        if (!mysqli_query($conn, $sql)) {
            die('Error al procesar el pedido: ' . mysqli_error($conn));
        }
    }

    // Vaciar el carrito después de procesar el pedido
    $_SESSION['cart'] = [];
}
?>
