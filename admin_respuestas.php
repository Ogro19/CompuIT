<?php
// Iniciar sesión si no está iniciada
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php'); // Redirigir si no es admin
    exit();
}

// Conexión a la base de datos
$servername = "localhost";  // El servidor donde corre MySQL (en XAMPP es localhost)
$username = "compuit";      // El usuario de MySQL
$password = "compuit123";   // La contraseña de MySQL
$dbname = "compuit_db";     // Nombre de la base de datos

try {
    // Corregido: usar $servername en lugar de $host
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Conexión fallida: ' . $e->getMessage();
    exit();
}

// Obtener los datos de las tablas 'contactanos' y 'unete'
$queryContactos = "SELECT * FROM contactanos";
$queryUnete = "SELECT * FROM unete";
$stmtContactos = $pdo->query($queryContactos);
$stmtUnete = $pdo->query($queryUnete);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuestas de Formularios - Admin</title>
    <link rel="stylesheet" href="css/admin_respuestas.css">
</head>
<body>

<h1>Respuestas de Formularios</h1>

<h2>Respuestas de Contacto</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Empresa</th>
            <th>Problema</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmtContactos->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                <td><?php echo htmlspecialchars($row['empresa']); ?></td>
                <td><?php echo htmlspecialchars($row['problema']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h2>Respuestas de Únete a Compu IT</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Mensaje</th>
            <th>CV</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmtUnete->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                <td><a href="<?php echo htmlspecialchars($row['cv_path']); ?>" target="_blank">Ver CV</a></td>
                <td><?php echo htmlspecialchars($row['fecha']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
