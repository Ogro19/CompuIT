<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CompuIT</title>
    <meta name="description" content="Inicia sesión en CompuIT para acceder a todos nuestros servicios digitales personalizados y administrar tu cuenta de usuario.">
    <meta name="keywords" content="login, iniciar sesión, CompuIT, acceso, usuario, contraseña">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="login-container">
    <img src="img/logo.jpg" alt="Logotipo de CompuIT">
    <h1>Iniciar Sesión en CompuIT</h1>
    
    <!-- Formulario de Login -->
    <form action="" method="POST">
        <input type="text" id="username" name="username" placeholder="Usuario" required>
        <input type="password" id="password" name="password" placeholder="Contraseña" required>
        <p>¿No tienes cuenta? <a href="registro.php" class="registro-link">Regístrate aquí</a></p>
        <div>
            <button type="submit" name="login">Ingresar</button>
        </div>
    </form>
</div>

<?php
// Incluir la conexión a la base de datos
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    ob_start();

    // Recoger los datos del formulario y sanitizarlos
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Buscar al usuario, incluyendo el campo de 'role'
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $row['password'])) {
            session_start();
            // Guardar el ID del usuario en la sesión
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['profileimage'] = $row['profileimage'];
            $_SESSION['role'] = $row['role'];  // Añadir el rol del usuario a la sesión
            session_write_close();

            // Redirigir al index.php después del login exitoso
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color:red;'>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p style='color:red;'>Usuario no encontrado.</p>";
    }

    $stmt->close();
    $conn->close();
    ob_end_flush();
}
?>

</body>
</html>
