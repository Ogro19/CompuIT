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


        <h1>Iniciar Sesión</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <!-- Campo Usuario -->
            <div class="input-group">
                <input type="text" 
                       id="username" 
                       name="username" 
                       placeholder="Usuario" 
                       required 
                       autocomplete="username">
            </div>

            <!-- Campo Contraseña -->
            <div class="input-group">
                <input type="password" 
                       id="password" 
                       name="password" 
                       placeholder="Contraseña" 
                       required 
                       autocomplete="current-password">
            </div>

            <!-- Botón de Ingreso -->
            <button type="submit" name="login">Ingresar</button>

            <!-- Enlace de Registro -->
            <div class="register-link">
                ¿No tienes cuenta? 
                <a href="registro.php">Regístrate aquí</a>
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
                // Log del error para el administrador
                error_log("Error en login.php: " . $e->getMessage());
            }

            // Cerrar la conexión
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
