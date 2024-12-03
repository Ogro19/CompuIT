<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Compu IT Marketing: Expertos en transformación digital. Servicios de SEO, marketing digital, diseño web y estrategias personalizadas para hacer crecer tu negocio.">
    <meta name="keywords" content="marketing digital, SEO, publicidad digital, redes sociales, desarrollo web, agencia marketing, diseño gráfico">
    <meta name="author" content="Compu IT Marketing">
    <meta name="theme-color" content="#1abc9c">
    
    <!-- Precargas optimizadas -->
    <link rel="preload" href="css/inicio.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" as="style">
    <link rel="preconnect" href="https://www.youtube.com">
    
    <!-- Estilos -->
    <link rel="stylesheet" href="css/inicio.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <title>Compu IT Marketing - Agencia Líder en Marketing Digital</title>
    <?php include('includes/header.php'); ?>
</head>
<body>
<div class="container">
    <?php include('includes/sidebar.php'); ?>

    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section" aria-label="Sección principal">
            <div class="hero-content">
                <div class="video-section">
                    <div class="video-wrapper">
                        <iframe 
                            title="Video introductorio de marketing digital"
                            src="https://www.youtube.com/embed/dEulwPWClIA"
                            width="400"
                            height="225"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <div class="text-section">
                    <h1>Compu IT Marketing, tu Agencia de Marketing Digital</h1>
                    <h2>Impulsamos tu empresa al liderazgo en cualquier mercado</h2>
                    <p>Con nuestra experiencia, transformamos tu marca en líder de su sector, sin importar el mercado en el que decidas competir.</p>
                    <a href="contactos.php" class="cta-button" data-tracking="hero-cta">¡Empezá tu expansión digital hoy!</a>
                </div>
            </div>
        </section>

        <!-- Servicios Section -->
        <section class="services-section" aria-label="Nuestros servicios">
            <header class="section-header">
                <h2>¿Qué Hacemos?</h2>
                <p><strong>Como agencia de marketing digital, estamos contigo en cada paso.</strong></p>
                <p>Te brindamos estrategias personalizadas que impulsan el crecimiento y la expansión de tu empresa.</p>
            </header>

            <div class="services-grid">
                <?php
                $services = [
                    [
                        'icon' => 'estra.png',
                        'title' => 'Estrategia Digital',
                        'desc' => 'Diseñamos estrategias personalizadas para alcanzar tus metas.',
                        'alt' => 'Icono de estrategia digital'
                    ],
                    [
                        'icon' => 'seo.png',
                        'title' => 'SEO',
                        'desc' => 'Optimización para motores de búsqueda que impulsa tu visibilidad.',
                        'alt' => 'Icono de SEO'
                    ],
                    [
                        'icon' => 'paid.png',
                        'title' => 'Publicidad Pagada',
                        'desc' => 'Maximiza tu ROI con campañas publicitarias efectivas.',
                        'alt' => 'Icono de publicidad'
                    ],
                    [
                        'icon' => 'sociales.png',
                        'title' => 'Redes Sociales',
                        'desc' => 'Conecta con tu audiencia y fortalece tu marca en plataformas sociales.',
                        'alt' => 'Icono de redes sociales'
                    ],
                    [
                        'icon' => 'grafico.png',
                        'title' => 'Diseño Gráfico',
                        'desc' => 'Creatividad visual que destaca tu marca y atrae clientes.',
                        'alt' => 'Icono de diseño gráfico'
                    ],
                    [
                        'icon' => 'web.png',
                        'title' => 'Desarrollo Web',
                        'desc' => 'Webs personalizadas y optimizadas para una experiencia de usuario superior.',
                        'alt' => 'Icono de desarrollo web'
                    ]
                ];

                foreach ($services as $service): ?>
                    <article class="service-card">
                        <div class="service-icon">
                            <img 
                                src="img/<?= htmlspecialchars($service['icon']) ?>" 
                                alt="<?= htmlspecialchars($service['alt']) ?>" 
                                loading="lazy"
                                width="64" 
                                height="64">
                        </div>
                        <h3><?= htmlspecialchars($service['title']) ?></h3>
                        <p><?= htmlspecialchars($service['desc']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <a href="servicios.php" class="services-button" data-tracking="services-cta">Más sobre nuestras soluciones</a>
        </section>

        <!-- Marketing seccion -->
        <section class="info-marketing" aria-label="Nuestra propuesta">
            <h2>Posicionamos Líderes</h2>
            <div class="marketing-content">
                <p class="marketing-description">
                    Somos tu socio estratégico para llevar tu empresa a la cima. Nuestra agencia de marketing digital 
                    crea planes personalizados que integran diseño, desarrollo web, posicionamiento SEO y generación 
                    de contenido de alto impacto.
                    <strong>¡Juntos logramos resultados reales!</strong>
                </p>
                <a href="blog.php" class="cta-button" data-tracking="blog-cta">¡Empecemos!</a>
            </div>
        </section>

        <!-- seccion Como lo hacemos  -->
        <section class="how-we-do-it" aria-label="Nuestra metodología">
            <header class="section-header">
                <h2>¿Cómo lo Hacemos?</h2>
                <p>Nos convertimos en tu aliado estratégico, acompañándote en cada etapa para lograr tus objetivos.</p>
            </header>

            <div class="how-we-do-it-content">
                <div class="methodology-image">
                    <img 
                        src="img/meta.jpg" 
                        alt="Equipo trabajando en estrategias de marketing" 
                        loading="lazy"
                        width="400" 
                        height="300">
                </div>

                <div class="methodology-steps">
                    <div class="accordion">
                        <?php
                        $steps = [
                            [
                                'step' => 1,
                                'title' => 'Evaluación',
                                'content' => 'Realizamos un análisis profundo que nos permite entender tus fortalezas y debilidades.'
                            ],
                            [
                                'step' => 2,
                                'title' => 'Estrategia',
                                'content' => 'Definimos estrategias personalizadas que aseguran el máximo impacto en el mercado.'
                            ],
                            [
                                'step' => 3,
                                'title' => 'Ejecución',
                                'content' => 'Implementamos las acciones necesarias utilizando las mejores herramientas.'
                            ],
                            [
                                'step' => 4,
                                'title' => 'Crecimiento',
                                'content' => 'Monitoreamos y optimizamos continuamente los resultados para asegurar el crecimiento.'
                            ]
                        ];

                        foreach ($steps as $step): ?>
                            <div class="accordion-item">
                                <button 
                                    class="accordion-button" 
                                    aria-expanded="false"
                                    aria-controls="content-<?= $step['step'] ?>">
                                    <span class="step-number"><?= $step['step'] ?></span>
                                    <span class="step-title"><?= htmlspecialchars($step['title']) ?></span>
                                </button>
                                <div 
                                    id="content-<?= $step['step'] ?>" 
                                    class="accordion-content"
                                    aria-hidden="true">
                                    <p><?= htmlspecialchars($step['content']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <a href="blog.php" class="cta-button" data-tracking="trends-cta">Tendencias</a>
        </section>

        <?php include('includes/footer.php'); ?>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Manejo del acordeón
    const initAccordion = () => {
        const buttons = document.querySelectorAll('.accordion-button');
        
        const closeAllAccordions = () => {
            buttons.forEach(button => {
                button.setAttribute('aria-expanded', 'false');
                const content = button.nextElementSibling;
                content.style.maxHeight = null;
                content.setAttribute('aria-hidden', 'true');
            });
        };

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                const content = button.nextElementSibling;
                
                closeAllAccordions();
                
                if (!isExpanded) {
                    button.setAttribute('aria-expanded', 'true');
                    content.style.maxHeight = `${content.scrollHeight}px`;
                    content.setAttribute('aria-hidden', 'false');
                }
            });
        });
    };

    // Inicialización
    initAccordion();
});
</script>
</body>
</html>