<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CompuIT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="img/logo.jpg" alt="CompuIT Logo" class="logo-image">
        </div>

        <h1>Bienvenido a CompuIT</h1>
        <p class="subtitle">Por favor, inicia sesión para continuar</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <!-- Campo Usuario -->
            <div class="input-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" placeholder="Ingresa tu usuario" required>
            </div>

            <!-- Campo Contraseña -->
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>

            <!-- Botón de Ingreso -->
            <button type="submit" name="login">Iniciar Sesión</button>

            <!-- Enlace de Registro -->
            <div class="register-link">
                ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            // Incluir la conexión a la base de datos
            require_once 'conexion.php';
            
            try {
                // Sanitizar y obtener datos del formulario
                $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
                $password = $_POST['password'];

                // Preparar la consulta
                $sql = "SELECT * FROM usuarios WHERE username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    
                    // Verificar contraseña
                    if (password_verify($password, $user['password'])) {
                        // Iniciar sesión
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['profileimage'] = $user['profileimage'];
                        $_SESSION['role'] = $user['role'];

                        // Redirigir al usuario
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "<div class='error-message'>La contraseña ingresada no es correcta</div>";
                    }
                } else {
                    echo "<div class='error-message'>No se encontró el usuario especificado</div>";
                }

                $stmt->close();
            } catch (Exception $e) {
                echo "<div class='error-message'>Error en el sistema. Por favor, intente más tarde.</div>";
                error_log("Error en login.php: " . $e->getMessage());
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
