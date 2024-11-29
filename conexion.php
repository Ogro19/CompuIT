<?php 
$servername = "localhost";  // Servidor donde corre MySQL
$username = "compuit";      // Usuario de MySQL
$password = "compuit123";   // Contraseña del usuario
$dbname = "compuit_db";     // Nombre de la base de datos

// Crear conexión utilizando MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

try {
    // Crear conexión utilizando PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>

