<?php
// Iniciar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

// Incluir la conexión a la base de datos
include('conexion.php');

// Obtener los datos del usuario
$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? ''; // Asegurarse de que la variable esté definida para evitar el error

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $profileImage = $_SESSION['profileimage'];

    // Manejar la subida de la nueva imagen
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $imageType = $_FILES['profileImage']['type'];
        $imageSize = $_FILES['profileImage']['size'];

        if (in_array($imageType, $allowedTypes) && $imageSize <= 500000) { // Limitar el tamaño a 500KB
            $imageName = uniqid() . '_' . basename($_FILES['profileImage']['name']);
            $imageTmpName = $_FILES['profileImage']['tmp_name'];
            $imageFolder = 'uploads/' . $imageName;

            if (move_uploaded_file($imageTmpName, $imageFolder)) {
                $profileImage = $imageFolder;
                $_SESSION['profileimage'] = $profileImage;
            } else {
                echo "<script>alert('Error al subir la imagen de perfil.');</script>";
            }
        } else {
            echo "<script>alert('Solo se permiten archivos de imagen (JPG, PNG, GIF) de hasta 500KB.');</script>";
        }
    }

    // Actualizar los datos del usuario en la base de datos
    $updateSQL = "UPDATE usuarios SET username='$newUsername', email='$newEmail', profileimage='$profileImage' WHERE username='$username'";

    if ($conn->query($updateSQL) === TRUE) {
        $_SESSION['username'] = $newUsername;
        $_SESSION['email'] = $newEmail;
        echo "<script>
                alert('Perfil actualizado correctamente.');
                window.location.href = 'index.php'; 
              </script>";
        exit();
    } else {
        echo "Error al actualizar el perfil: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edita tu perfil de usuario, cambia tu nombre de usuario, correo electrónico y actualiza tu foto de perfil en CompuIT.">
    <meta name="keywords" content="editar perfil, CompuIT, actualización de perfil, cambiar imagen de perfil">
    <title>Editar Perfil de Usuario - CompuIT</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>

<div class="edit-profile-container">
    <h1>Editar Perfil</h1> <!-- Mejora de SEO con h1 -->
    <h2>Actualiza tu información personal</h2> <!-- Subtítulo para mejor estructura SEO -->
    <form action="perfil.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="profileImage">Subir nueva foto de perfil:</label>
        <input type="file" name="profileImage" accept="image/*" aria-label="Subir nueva foto de perfil"> <!-- Etiqueta alt mejorada -->

        <button type="submit" class="btn-save-changes">Guardar Cambios</button>
    </form>
</div>

</body>
</html>
