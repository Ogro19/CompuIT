<?php
$servername = "localhost";  // El servidor donde corre MySQL (en XAMPP es localhost)
$username = "root";         // El usuario de MySQL (por defecto en XAMPP es root)
$password = "";             // Sin contraseña (en XAMPP por defecto root no tiene contraseña)
$dbname = "compuIT_db";     // Nombre de la base de datos que creaste

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


?>
