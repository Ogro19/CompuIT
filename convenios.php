<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Convenios universitarios y alianzas estratégicas con Compu IT Marketing - Desarrollo profesional y prácticas en marketing digital">
    <title>Convenios con Universidades - Compu IT Marketing</title>
    <!-- Preload critical assets -->
    <link rel="preload" href="css/convenios.css" as="style">
    <link rel="preload" href="https://unpkg.com/swiper/swiper-bundle.min.css" as="style">
    <link rel="preload" href="https://unpkg.com/swiper/swiper-bundle.min.js" as="script">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/convenios.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <?php require_once 'includes/header.php'; ?>
</head>
<body>

<div class="container">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="main-content">
        <section class="cta-header">
            <h1>Construye Alianzas Estratégicas con el Futuro del Talento</h1>
            <p>Colabora con instituciones de renombre y asegura prácticas de calidad para tus estudiantes.</p>
            <a href="#form-convention" class="cta-button-header">¡Conviértete en nuestro socio estratégico!</a>
        </section>

        <section class="universidades-carousel" aria-label="Universidades asociadas">
            <h2>Universidades con las que tenemos convenios</h2>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $universities = [
                        ['itm.jpg', 'Instituto Tecnológico de Mexicali (ITM)'],
                        ['uabc.png', 'Universidad Autónoma de Baja California (UABC)'],
                        ['viz.jpg', 'Universidad Vizcaya de las Américas'],
                        ['xochicalco.jpg', 'Universidad Xochicalco'],
                        ['cetys.jpg', 'Centro de Enseñanza Técnica y Superior (CETYS)'],
                        ['UPN.png', 'Universidad Pedagógica Nacional (UPN)']
                    ];

                    foreach ($universities as [$img, $name]) {
                        echo "<div class='swiper-slide'>
                                <img src='img2/{$img}' alt='Logo de {$name}' loading='lazy'>
                                <p>{$name}</p>
                            </div>";
                    }
                    ?>
                </div>
                <div class="swiper-button-next" aria-label="Siguiente universidad"></div>
                <div class="swiper-button-prev" aria-label="Universidad anterior"></div>
                <div class="swiper-pagination"></div>
            </div>
        </section>

        <section class="info-adicional">
            <h2>Lo que ofrecemos como empresa de Marketing Digital</h2>
            <p>En Compu IT Marketing, entendemos la importancia de la colaboración estratégica con instituciones educativas de renombre.</p>
            
            <div class="benefits-grid">
                <?php
                $benefits = [
                    ['Colaboración en Proyectos Reales', 'Ofrecemos a los estudiantes la oportunidad de participar en proyectos de marketing digital en tiempo real.'],
                    ['Asesoría y Capacitación Especializada', 'Proveemos talleres y cursos de capacitación dirigidos por expertos en marketing digital.'],
                    ['Acceso a Herramientas Avanzadas', 'Acceso exclusivo a herramientas tecnológicas y software de marketing.'],
                    ['Publicidad y Posicionamiento', 'Servicios de SEO y campañas de publicidad digital para mejorar presencia online.'],
                    ['Prácticas Profesionales', 'Conectamos estudiantes con oportunidades de prácticas profesionales.'],
                    ['Innovación Continua', 'Vanguardia en tecnologías emergentes de marketing digital.']
                ];

                foreach ($benefits as $index => [$title, $description]) {
                    echo "<div class='benefit-item'>
                            <h3>" . ($index + 1) . ". {$title}</h3>
                            <p>{$description}</p>
                          </div>";
                }
                ?>
            </div>
        </section>

        <section class="beneficios-convenios">
            <h2>¿Por qué establecer un convenio con nosotros?</h2>
            <div class="benefits-grid">
                <?php
                $iconBenefits = [
                    ['talentos.png', 'Acceso a talento altamente calificado'],
                    ['colaboracion.png', 'Oportunidades de colaboración en proyectos innovadores'],
                    ['practica.png', 'Prácticas profesionales para estudiantes'],
                    ['capacitacion.png', 'Capacitación y asesoría especializada']
                ];

                foreach ($iconBenefits as [$icon, $text]) {
                    echo "<div class='benefit-item'>
                            <img src='img2/{$icon}' alt='Icono de {$text}' loading='lazy'>
                            <p>{$text}</p>
                          </div>";
                }
                ?>
            </div>
        </section>

        <section id="form-convention" class="new-convention">
            <h2>¿Quieres establecer un convenio con nosotros?</h2>
            <p>Si representas una universidad y estás interesado en colaborar con Compu IT Marketing, por favor completa el siguiente formulario:</p>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form-convention">
                <?php
                $formFields = [
                    ['universidad', 'text', 'Nombre de la Universidad'],
                    ['contacto', 'text', 'Nombre del Contacto'],
                    ['email', 'email', 'Correo Electrónico'],
                    ['telefono', 'tel', 'Teléfono de Contacto']
                ];

                foreach ($formFields as [$id, $type, $label]) {
                    echo "<div class='form-group'>
                            <label for='{$id}'>{$label}:</label>
                            <input type='{$type}' id='{$id}' name='{$id}' required>
                          </div>";
                }
                ?>

                <div class="form-group">
                    <label for="intereses">¿En qué áreas estás interesado en colaborar?</label>
                    <select id="intereses" name="intereses" required>
                        <option value="practicas">Prácticas Profesionales</option>
                        <option value="investigacion">Proyectos de Investigación</option>
                        <option value="capacitacion">Capacitación y Consultoría</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="mensaje">Descripción de la Propuesta de Convenio:</label>
                    <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
                </div>

                <button type="submit" class="cta-button">Solicita tu convenio y accede a talento joven</button>
            </form>
        </section>
    </main>

    <?php require_once 'includes/footer.php'; ?>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.swiper-container', {
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        slidesPerView: 1,
        spaceBetween: 30,
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            968: {
                slidesPerView: 3,
            }
        }
    });
});
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'conexion.php';
    
    try {
        $stmt = $conn->prepare("INSERT INTO convenios_universidades (nombre_universidad, contacto, correo, telefono, area_colaboracion, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssss", 
            $_POST['universidad'],
            $_POST['contacto'],
            $_POST['email'],
            $_POST['telefono'],
            $_POST['intereses'],
            $_POST['mensaje']
        );
        
        if ($stmt->execute()) {
            echo "<script>alert('¡Propuesta enviada con éxito!');</script>";
        } else {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        error_log("Error en el formulario de convenios: " . $e->getMessage());
        echo "<script>alert('Error al procesar la solicitud. Por favor, inténtelo nuevamente.');</script>";
    } finally {
        $conn->close();
    }
}
?>
</body>
</html>