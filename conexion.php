<?php 
$servername = "localhost";  // El servidor donde corre MySQL (en XAMPP es localhost)
$username = "compuit";         // El usuario de MySQL (por defecto en XAMPP es root)
$password = "compuit123";             // Sin contraseña (en XAMPP por defecto root no tiene contraseña)
$dbname = "compuit_db";     // Nombre de la base de datos que creaste

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


?>
