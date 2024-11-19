<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Procesar el formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'];
    $resumen = $_POST['resumen'];
    $youtube_link = $_POST['youtube_link'];

    // Validar que el enlace sea un URL de YouTube
    if (preg_match("/^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/", $youtube_link)) {
        // Insertar el video en la base de datos
        $sql = "INSERT INTO videos_blog (titulo, resumen, youtube_link) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $titulo, $resumen, $youtube_link);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Video subido exitosamente.');</script>";
    } else {
        echo "<script>alert('Por favor, introduce un enlace válido de YouTube.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Video al Blog</title>
    <link rel="stylesheet" href="tiendaCSS/video.css">
    <?php include('includes/header.php'); ?>

</head>
<body>

<h1>Subir Nuevo Video al Blog</h1>
<?php include 'includes/sidebar.php'; ?>


<form action="subir_video.php" method="POST">
    <label for="titulo">Título del Video:</label>
    <input type="text" id="titulo" name="titulo" required>

    <label for="resumen">Resumen del Video:</label>
    <textarea id="resumen" name="resumen" rows="4" required></textarea>

    <label for="youtube_link">Enlace de YouTube:</label>
    <input type="url" id="youtube_link" name="youtube_link" placeholder="https://www.youtube.com/watch?v=..." required>

    <button type="submit">Subir Video</button>
</form>

<a href="blog.php">Volver al blog</a>
<?php include 'includes/footer.php'; ?>

</body>
</html>
