
<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Regístrate en CompuIT y crea tu cuenta para acceder a nuestros servicios digitales. Sube tu imagen de perfil y gestiona tus datos.">
    <meta name="keywords" content="registro de usuario, CompuIT, crear cuenta, marketing digital">
    <title>Registro de Usuario - CompuIT</title>
    <link rel="stylesheet" href="css/registro.css">
</head>
<body>

<div class="register-container">
    <h1>Registro de Usuario</h1> <!-- Mejorar SEO con h1 -->
    <h2>Crea tu cuenta en CompuIT</h2> <!-- Añadir subtítulo para SEO -->
    
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="profileImage">Sube tu foto de perfil:</label>
        <input type="file" id="profileImage" name="profileImage" accept="image/*" aria-label="Sube tu foto de perfil">

        <button type="submit" class="btn-register">Registrar</button>
    </form>
</div>

<?php
// Incluir la conexión a la base de datos
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Cifrar la contraseña
    $profileImage = '';
    $role = "usuario";  // Asignar el rol por defecto como 'usuario'

    // Verificar si el usuario o el correo ya existen
    $checkUser = "SELECT * FROM usuarios WHERE email=? OR username=?";
    $stmt = $conn->prepare($checkUser);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('El nombre de usuario o correo electrónico ya están en uso.');</script>";
    } else {
        // Subida de la imagen de perfil con seguridad
        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $imageType = $_FILES['profileImage']['type'];
            $imageSize = $_FILES['profileImage']['size'];

            if (in_array($imageType, $allowedTypes) && $imageSize <=  2097152) {
                $imageName = uniqid() . '_' . basename($_FILES['profileImage']['name']);
                $imageTmpName = $_FILES['profileImage']['tmp_name'];
                $imageFolder = 'uploads/' . $imageName;

                if (move_uploaded_file($imageTmpName, $imageFolder)) {
                    $profileImage = $imageFolder;
                } else {
                    echo "<script>alert('Error al subir la imagen de perfil.');</script>";
                }
            } else {
                echo "<script>alert('Solo se permiten archivos de imagen (JPG, PNG, GIF) de hasta 500KB.');</script>";
            }
        }

        // Insertar datos en la base de datos con consulta preparada, incluyendo el rol
        $sql = "INSERT INTO usuarios (username, email, password, profileimage, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $password, $profileImage, $role);  // Añadir el rol como 'usuario'

        if ($stmt->execute()) {
            // Iniciar sesión automáticamente
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['profileimage'] = $profileImage;
            $_SESSION['role'] = $role;  // Asignar el rol a la sesión

            // Redirigir a la página principal
            echo "<script>alert('Registro exitoso. ¡Bienvenido!'); window.location.href = 'index.php';</script>";
        } else {
            echo "Error en la consulta SQL: " . $conn->error;
        }
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
