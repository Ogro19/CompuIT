<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agencia de Marketing Digital - Sobre Nosotros | Compu IT Growth Agency</title>
    <meta name="description" content="Conoce más sobre Compu IT Growth Agency, líderes en marketing digital. Descubre nuestra misión, visión, valores, y equipo que nos impulsa a transformar negocios a través de la innovación tecnológica.">
    <link rel="stylesheet" href="css/nosotros.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <?php include('includes/header.php'); ?>
</head>
<body>

    <div class="container">
        <!-- Menú lateral -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Encabezado de la empresa -->
            <div class="company-header">
               <h1>Sobre Nosotros</h1>
               <h1>Compu IT Growth Agency</h1>
            </div>

           <!-- Sección de misión, visión y propósito -->
<section class="info-section">
    <div>
        <i class="icon fas fa-bullseye"></i>
        <div>
            <h2>Misión</h2>
            <p>Ofrecer soluciones tecnológicas innovadoras y personalizadas que impulsen el crecimiento de nuestros clientes y optimicen sus procesos internos.</p>
        </div>
    </div>
    <div>
        <i class="icon fas fa-eye"></i>
        <div>
            <h2>Visión</h2>
            <p>Ser una empresa de referencia global en servicios de tecnología, reconocida por nuestra capacidad de generar valor a través de la innovación.</p>
        </div>
    </div>
    <div>
        <i class="icon fas fa-globe"></i>
        <div>
            <h2>Propósito</h2>
            <p>Generar una cadena de negocios expertos en mercadotecnia digital, sostenibles, económica, ambiental y socialmente responsables.</p>
        </div>
    </div>
</section>


            <!-- Sección de Nuestro Trabajo -->
            <div class="communication-section">
                <h2>Nuestro Trabajo</h2>
                <div class="communication-tabs">
                    <button class="tab active" data-target="marketing">MARKETING</button>
                    <button class="tab" data-target="redes">REDES</button>
                    <button class="tab" data-target="diseno">DISEÑO</button>
                </div>

                <!-- Contenidos dinámicos de las pestañas -->
                <div class="communication-content">
                    <div class="tab-content" id="marketing">
                        <div class="communication-info">
                            <h2>Posición de liderazgo</h2>
                            <ul>
                                <li><strong>Estrategia personalizada</strong> para tu rubro y público objetivo.</li>
                                <li><strong>Establecemos tu customer journey</strong> para fidelizar a tus clientes.</li>
                                <li><strong>Presencia digital</strong> en los canales correctos que potencien tus objetivos.</li>
                                <li><strong>Reportes mensuales</strong>, análisis y optimizaciones.</li>
                            </ul>
                        </div>
                        <div class="communication-image">
                            <img src="img/mk.png" alt="Marketing" loading="lazy">
                        </div>
                    </div>

                    <div class="tab-content" id="redes" style="display: none;">
                        <div class="communication-info">
                            <h2>Contenido de valor</h2>
                            <ul>
                                <li><strong>Análisis de competencia, hashtags y tendencias</strong> relevantes para atraer a tu público.</li>
                                <li><strong>Optimización de tus perfiles</strong> para alinearlos a tu marca.</li>
                                <li><strong>Planning, comunicación y desarrollo</strong> de tu voz, tono y mensaje.</li>
                                <li><strong>Investigación y control del comportamiento</strong> de tu público.</li>
                            </ul>
                        </div>
                        <div class="communication-image">
                            <img src="img/rds.png" alt="Redes Sociales" loading="lazy">
                        </div>
                    </div>

                    <div class="tab-content" id="diseno" style="display: none;">
                        <div class="communication-info">
                            <h2>Comunicación Atractiva</h2>
                            <ul>
                                <li><strong>Investigación y desarrollo de tu Identidad visual</strong> para comunicar el mensaje correcto en cada canal.</li>
                                <li><strong>Sitios web responsive</strong> que unifican estética, navegabilidad y tecnología.</li>
                                <li><strong>Landing pages</strong> que cautivan y convierten exitosamente.</li>
                                <li><strong>Producción audiovisual</strong> con enfoque estratégico y piezas únicas.</li>
                            </ul>
                        </div>
                        <div class="communication-image">
                            <img src="img/dmk.jpg" alt="Diseño" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Historia de la Empresa -->
            <div class="history-section">
                <h2>Nuestra Historia</h2>
                <p>Compu IT Growth Agency comenzó como una visión para revolucionar el marketing digital con soluciones tecnológicas innovadoras. Desde nuestro inicio en 2010, hemos ayudado a cientos de empresas a crecer y alcanzar sus objetivos. A partir del 2020, hemos sido reconocidos como líderes en innovación en marketing digital, adaptándonos a los desafíos y oportunidades del mercado global.</p>
                <div class="timeline">
                    <div class="timeline-event">
                    </div>
                </div>
            </div>

           <!-- Sección de Valores y Cultura -->
<section class="values-section">
    <h2>Nuestros Valores</h2>
    <p>“Marcamos tendencia en la transformación de negocios”</p>
    <div class="values-grid">
        <?php
        $values = [
            "Trabajo en equipo" => "Colaboramos para alcanzar objetivos comunes, potenciando las fortalezas de cada miembro del equipo.",
            "Comunicación" => "Mantenemos un flujo de información claro y constante, tanto interno como con nuestros clientes.",
            "Innovación" => "Fomentamos la creatividad y buscamos siempre nuevas formas de hacer las cosas.",
            "Iniciativa" => "Tomamos la iniciativa para proponer soluciones proactivas y anticiparnos a las necesidades del mercado.",
            "Libertad" => "Valoramos la libertad de pensamiento y la flexibilidad para adaptar nuestras estrategias a cada proyecto.",
            "Responsabilidad" => "Asumimos nuestras acciones y resultados con responsabilidad y compromiso hacia nuestros clientes y el equipo.",
            "Excelencia" => "Nos esforzamos por alcanzar los más altos estándares en todo lo que hacemos.",
            "Pasión" => "Nos apasiona lo que hacemos y eso se refleja en los resultados que entregamos.",
            "Creatividad" => "Desarrollamos soluciones creativas que nos permiten superar las expectativas de nuestros clientes.",
            "Responsabilidad social" => "Contribuimos activamente al desarrollo sostenible y al bienestar social a través de nuestras acciones y valores."
        ];
        foreach ($values as $title => $description): ?>
            <div class="value-item">
                <h3><?php echo $title; ?></h3>
                <p><?php echo $description; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>


            <!-- Sección del equipo -->
            <div class="team-section">
                <h2>Conoce a Nuestro Equipo</h2>
                <div class="team-members">
                    <div class="team-member">
                        <img src="img/edgar.png" alt="Edgar Ramirez" loading="lazy">
                        <h3>Edgar Ramirez Arizaga</h3>
                        <p>CEO y Fundador</p>
                    </div>
                    <div class="team-member">
                        <img src="img/gabriel.png" alt="Gabriel Ramirez" loading="lazy">
                        <h3>Gabriel Ramirez</h3>
                        <p>Co-fundador</p>
                    </div>
                </div>
            </div>

            <!-- Sección de Contacto Rápido -->
            <div class="contact-cta">
                <h2>¿Listo para Crecer con Nosotros?</h2>
                <p>Contáctanos hoy mismo y descubre cómo podemos ayudarte a llevar tu empresa al siguiente nivel.</p>
                <a href="contactos.php" class="cta-button">Contáctanos</a>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript para manejo de pestañas -->
    <script>
        document.querySelectorAll('.tab').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');

                button.classList.add('active');
                document.getElementById(button.dataset.target).style.display = 'flex'; // Cambiar a 'flex' para mantener el diseño con imagen y texto
            });
        });
    </script>
</body>
</html>
