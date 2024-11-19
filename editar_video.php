<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener el ID del video desde la URL
if (!isset($_GET['id'])) {
    echo "No se ha especificado ningún video para editar.";
    exit();
}
$video_id = $_GET['id'];

// Obtener los detalles del video para prellenar el formulario
$sql = "SELECT * FROM videos_blog WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();
$video = $result->fetch_assoc();

if (!$video) {
    echo "Video no encontrado.";
    exit();
}

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'];
    $resumen = $_POST['resumen'];
    $youtube_link = $_POST['youtube_link'];

    // Validar el enlace de YouTube
    if (preg_match("/^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/", $youtube_link)) {
        $update_sql = "UPDATE videos_blog SET titulo = ?, resumen = ?, youtube_link = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $titulo, $resumen, $youtube_link, $video_id);
        $stmt->execute();
        echo "<script>alert('Video actualizado exitosamente.'); window.location.href = 'blog.php';</script>";
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
    <title>Editar Video</title>
    <link rel="stylesheet" href="tiendaCSS/editarV.css">
    <?php include('includes/header.php'); ?>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>Editar Video</h1>
            <?php include 'includes/sidebar.php'; ?>
            <form action="editar_video.php?id=<?php echo $video_id; ?>" method="POST" onsubmit="showLoading()">
                <label for="titulo">Título del Video:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($video['titulo']); ?>" required>

                <label for="resumen">Resumen del Video:</label>
                <textarea id="resumen" name="resumen" rows="4" required><?php echo htmlspecialchars($video['resumen']); ?></textarea>

                <label for="youtube_link">Enlace de YouTube:</label>
                <input type="url" id="youtube_link" name="youtube_link" value="<?php echo htmlspecialchars($video['youtube_link']); ?>" required>

                <button type="submit" id="saveButton">Guardar Cambios</button>
            </form>
            <a href="blog.php">Volver al blog</a>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>

    <!-- Script para la validación en vivo y feedback de carga -->
    <script>
        // Validación en vivo para el campo de título
        document.getElementById('titulo').addEventListener('input', function() {
            const maxLength = 100;
            if (this.value.length > maxLength) {
                alert('El título debe tener menos de ' + maxLength + ' caracteres.');
                this.value = this.value.substring(0, maxLength); // Limitar el texto al tamaño máximo
            }
        });

        // Feedback visual al guardar cambios
        function showLoading() {
            const saveButton = document.getElementById('saveButton');
            saveButton.innerText = 'Guardando...';
            saveButton.disabled = true; // Desactivar el botón mientras se procesa
        }
    </script>
</body>
</html>
