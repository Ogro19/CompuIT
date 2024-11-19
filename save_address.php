<?php
// Incluir la conexión a la base de datos
include('conexion.php');

// Solo iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está autenticado
    header('Location: login.php');
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $zip = htmlspecialchars($_POST['zip']);
    
    // Validar que los campos no estén vacíos
    if (!empty($address) && !empty($city) && !empty($zip)) {
        // Actualizar los datos en la tabla 'usuarios'
        $sql = "UPDATE usuarios SET direccion = ?, ciudad = ?, codigo_postal = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $address, $city, $zip, $user_id);
        
        if ($stmt->execute()) {
            // Guardar la dirección en la sesión
            $_SESSION['direccion_entrega'] = $address . ', ' . $city . ', ' . $zip;

            // Redirigir de vuelta a ecommerce.php con la dirección actualizada
            header('Location: ecommerce.php');
            exit();
        } else {
            echo "Error al actualizar la dirección: " . $conn->error;
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}


?>
