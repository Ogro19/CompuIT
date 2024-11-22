<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Compu IT Marketing Digital - Servicios de marketing digital y desarrollo web">
    <meta name="theme-color" content="#2c3e50">
    <meta name="author" content="Compu IT Marketing Digital">
    <meta property="og:title" content="Compu IT Marketing Digital">
    <meta property="og:description" content="Servicios profesionales de marketing digital y desarrollo web">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_MX">
    
    <title>Compu IT Marketing Digital - Inicio</title>
    
    <!-- Precargar recursos críticos -->
    <link rel="preload" href="css/footer.css" as="style">
    <link rel="preload" href="img/facebook.png" as="image">
    <link rel="preload" href="img/instagram.png" as="image">
    
    <!-- Estilos -->
    <link rel="stylesheet" href="css/footer.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/favicon.png">
</head>
<body>
    <!-- Contenido principal -->
    <main class="main-content">
        <!-- Aquí va el contenido principal -->
    </main>

    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="footer-container">
            <!-- Sección de redes sociales -->
            <section class="social-section">
                <h2 class="social-title">Síguenos en nuestras redes</h2>
                
                <nav class="social-media" aria-label="Redes sociales">
                    <ul class="social-list">
                        <li>
                            <a href="https://www.facebook.com/compuitmarketingdigital" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="social-link"
                               aria-label="Síguenos en Facebook">
                                <img src="img/facebook.png" 
                                     alt=""
                                     width="32"
                                     height="32"
                                     loading="lazy"
                                     class="social-icon">
                                <span class="social-text">Facebook</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/compuitmarketingdigital/" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="social-link"
                               aria-label="Síguenos en Instagram">
                                <img src="img/instagram.png" 
                                     alt=""
                                     width="32"
                                     height="32"
                                     loading="lazy"
                                     class="social-icon">
                                <span class="social-text">Instagram</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:compuit23@gmail.com" 
                               class="social-link"
                               aria-label="Contáctanos por correo">
                                <img src="img/gmail.png" 
                                     alt=""
                                     width="32"
                                     height="32"
                                     loading="lazy"
                                     class="social-icon">
                                <span class="social-text">Correo</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/@gabyr.468" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="social-link"
                               aria-label="Visita nuestro canal de YouTube">
                                <img src="img/youtube.png" 
                                     alt=""
                                     width="32"
                                     height="32"
                                     loading="lazy"
                                     class="social-icon">
                                <span class="social-text">YouTube</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://mx.linkedin.com/in/compuit-maketing-digital-99b0b824b" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="social-link"
                               aria-label="Conéctate en LinkedIn">
                                <img src="img/linkedin.png" 
                                     alt=""
                                     width="32"
                                     height="32"
                                     loading="lazy"
                                     class="social-icon">
                                <span class="social-text">LinkedIn</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </section>

            <!-- Sección de derechos de autor -->
            <section class="copyright-section">
                <p class="copyright-text">
                    <small>© <?php echo date('Y'); ?> Compu IT Marketing Digital. Todos los derechos reservados.</small>
                </p>
            </section>
        </div>
    </footer>

    <!-- Script para el año dinámico en el copyright -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Implementar lazy loading para imágenes que estén fuera de la vista
            if ('loading' in HTMLImageElement.prototype) {
                const images = document.querySelectorAll('img[loading="lazy"]');
                images.forEach(img => {
                    img.src = img.src;
                });
            }
        });
    </script>
</body>
</html>
