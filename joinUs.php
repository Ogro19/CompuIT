<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulación - Compu IT Marketing</title>
    <meta name="description" content="Únete al equipo de Compu IT Marketing. Envía tu postulación y forma parte de una agencia en constante evolución, enfocada en el marketing digital.">
    <meta name="keywords" content="postulación, empleo, marketing digital, trabajo, unirse a Compu IT">
    <link rel="stylesheet" href="css/joinUs.css">
    <?php include('includes/Header.php'); ?>
</head>
<body>

    <!-- Barra lateral -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Sección de Bienvenida con Imagen de Fondo -->
        <div class="welcome-section">
            <h1>Únete a Compu IT Marketing</h1>
            <p>Estamos en constante evolución y buscamos talento que quiera dejar huella en el marketing digital. ¿Te unes a nosotros?</p>
            <button class="cta-button" onclick="document.getElementById('form-section').scrollIntoView({ behavior: 'smooth' });">QUIERO SER PARTE</button>
        </div>

        <!-- Contenedor del Formulario Mejorado -->
        <div id="form-section" class="form-container">
            <h1>¿Quieres unirte a nuestro equipo?</h1>
            <p class="subtitle">Trabaja con un equipo experto en marketing digital. Postúlate hoy y lleva tu carrera al siguiente nivel.</p>
            
            <form action="" method="POST" enctype="multipart/form-data" class="form-section">
                <input type="text" name="nombre" placeholder="Nombre Completo" required>
                <input type="email" name="email" placeholder="Correo Electrónico" required>
                <input type="text" name="telefono" placeholder="Teléfono" required>
                <textarea name="mensaje" placeholder="Cuéntanos por qué quieres unirte" rows="4" required></textarea>
                <div class="file-upload">
                    <label for="resume">Adjunta tu CV (PDF o Word, máximo 2MB):</label>
                    <input type="file" name="resume" id="resume" required>
                </div>
                <button type="submit" class="submit-button">Enviar Solicitud</button>
            </form>
        </div>
    </div>

    <!-- Incluir el footer global -->
    <?php include('includes/footer.php'); ?>

    <?php
    // Incluir la conexión a la base de datos
    include('conexion.php');

    // Verificar si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recoger los datos del formulario
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $mensaje = mysqli_real_escape_string($conn, $_POST['mensaje']);
        $cv_path = '';

        // Verificar si se subió un archivo
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $fileType = $_FILES['resume']['type'];
            $fileSize = $_FILES['resume']['size'];

            if (in_array($fileType, $allowedTypes) && $fileSize <= 2000000) { // Limitar el tamaño a 2MB
                $fileName = uniqid() . '_' . basename($_FILES['resume']['name']);
                $fileTmpName = $_FILES['resume']['tmp_name'];
                $fileFolder = 'uploads/' . $fileName;

                // Asegurarse de que la carpeta 'uploads' exista y tenga permisos de escritura
                if (move_uploaded_file($fileTmpName, $fileFolder)) {
                    $cv_path = $fileFolder; // Guardar la ruta del archivo en la variable
                } else {
                    echo "<script>alert('Error al subir el archivo del CV.');</script>";
                }
            } else {
                echo "<script>alert('Solo se permiten archivos PDF o Word de hasta 2MB.');</script>";
            }
        }

        // Insertar los datos en la base de datos con una consulta preparada
        $sql = "INSERT INTO unete (nombre, email, telefono, mensaje, cv_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $email, $telefono, $mensaje, $cv_path);

        if ($stmt->execute()) {
            echo "<script>alert('Tu postulación ha sido enviada exitosamente.');</script>";
        } else {
            echo "Error en la consulta SQL: " . $conn->error;
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
