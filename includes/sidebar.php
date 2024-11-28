<?php
// Verificar si las funciones ya están definidas
if (!function_exists('initSession')) {
    function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
    }
}

if (!function_exists('renderUserProfile')) {
    function renderUserProfile() {
        $defaultImage = 'uploads/default-profile.png';
        $profileImage = $_SESSION['profileimage'] ?? $defaultImage;
        $username = $_SESSION['username'] ?? 'Invitado';
        
        return sprintf(
            '<div class="profile-image-container">
                <img src="%s" alt="Perfil" class="profile-image" loading="lazy">
             </div>
             <div class="username-container">
                <p class="username">%s</p>
             </div>',
            htmlspecialchars($profileImage),
            htmlspecialchars($username)
        );
    }
}

if (!function_exists('generateMenuItem')) {
    function generateMenuItem($icon, $url, $text) {
        return sprintf(
            '<li>
                <img src="img/%s" alt="%s" loading="lazy">
                <a href="%s"><span>%s</span></a>
             </li>',
            htmlspecialchars($icon),
            htmlspecialchars($text),
            htmlspecialchars($url),
            htmlspecialchars($text)
        );
    }
}

// Definir los elementos del menú común (solo si no está definido)
if (!isset($commonMenuItems)) {
    $commonMenuItems = [
        ['home.png', 'index.php', 'Inicio'],
        ['perfil.png', 'nosotros.php', 'Nosotros'],
        ['convenio.png', 'convenios.php', 'Convenios'],
        ['servicios.png', 'servicios.php', 'Servicios'],
        ['e-commerce.png', 'ecommerce.php', 'E-commerce'],
        ['blog.png', 'blog.php', 'Blog'],
        ['encuesta.png', 'joinUs.php', 'Únete a Compu IT'],
        ['contacto.png', 'contactos.php', 'Contáctanos'],
        ['perfil2.png', 'perfil.php', 'Perfil'],
        ['logout.png', '../logout.php', 'Salir'] // Ruta corregida aquí
    ];
}

// Inicializar sesión
initSession();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de navegación de Compu IT Marketing Digital">
    <title>Compu IT Marketing Digital - Sidebar</title>
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>

<!-- Barra lateral -->
<div class="sidebar">
    <div class="sidebar-inner">
        <!-- Perfil de usuario -->
        <?php echo renderUserProfile(); ?>

        <nav>
            <ul class="menu">
                <?php if (!isset($_SESSION['username'])): ?>
                    <?php
                    echo generateMenuItem('inicio.png', 'login.php', 'Iniciar Sesión');
                    echo generateMenuItem('registro.png', 'registro.php', 'Registrarse');
                    ?>
                <?php endif; ?>

                <!-- Opciones de administrador -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <?php
                    echo generateMenuItem('producto.png', 'admin_products.php', 'Gestión de Productos');
                    echo generateMenuItem('video.png', 'subir_video.php', 'Subir Video');
                    echo generateMenuItem('seo.png', 'panel_seo.php', 'Panel SEO');
                    // Nuevo ítem para el Panel de Respuestas
                    echo generateMenuItem('respuesta.png', 'admin_respuestas.php', 'Respuestas');
                    ?>
                <?php endif; ?>

                <?php
                // Panel SEO (disponible para todos)

                // Generar menú común
                foreach ($commonMenuItems as $item) {
                    echo generateMenuItem($item[0], $item[1], $item[2]);
                }
                ?>
            </ul>
        </nav>
    </div>
</div>

</body>
</html>
