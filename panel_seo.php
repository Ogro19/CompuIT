<?php
// Iniciar sesión y verificar rol de usuario
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Funciones para análisis SEO

// Obtener el tiempo de carga de la página
function getPageLoadTime($url) {
    $start = microtime(true);
    @file_get_contents($url);
    $end = microtime(true);
    return round(($end - $start) * 1000, 2); // Milisegundos
}

// Verificar si la URL es amigable
function isFriendlyUrl($url) {
    return preg_match('/^[a-z0-9\-\/]+$/', $url);
}

// Obtener el contenido de la URL con manejo de errores
function fetchContent($url) {
    $context = stream_context_create(['http' => ['timeout' => 5]]); // Limitar el tiempo de espera
    $content = @file_get_contents($url, false, $context);
    return $content === false ? null : $content;
}

// Contar enlaces internos y externos
function getLinkCounts($url) {
    $internalLinks = $externalLinks = 0;
    $host = parse_url($url, PHP_URL_HOST);
    $pageContent = fetchContent($url);

    if ($pageContent) {
        $dom = new DOMDocument();
        @$dom->loadHTML($pageContent);
        foreach ($dom->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');
            if (strpos($href, 'http') === 0) {
                $linkHost = parse_url($href, PHP_URL_HOST);
                ($linkHost === $host) ? $internalLinks++ : $externalLinks++;
            }
        }
    }
    return ['internal' => $internalLinks, 'external' => $externalLinks];
}

// Verificar si el sitio utiliza HTTPS
function checkHttps($url) {
    return parse_url($url, PHP_URL_SCHEME) === 'https';
}

// Verificar si el sitio tiene un sitemap.xml
function checkSitemap($url) {
    $sitemapUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . '/sitemap.xml';
    $headers = @get_headers($sitemapUrl);
    return $headers && strpos($headers[0], '200') !== false;
}

// Obtener tamaños de recursos como imágenes
function getResourceSizes($url) {
    $pageContent = fetchContent($url);
    if (!$pageContent) return [];
    
    $sizes = [];
    $dom = new DOMDocument();
    @$dom->loadHTML($pageContent);
    foreach ($dom->getElementsByTagName('img') as $img) {
        $src = $img->getAttribute('src');
        if ($src && strpos($src, 'http') !== 0) {
            $src = rtrim($url, '/') . '/' . ltrim($src, '/');
        }
        $imageSize = @get_headers($src, 1);
        if ($imageSize && isset($imageSize['Content-Length'])) {
            $sizes[$src] = round($imageSize['Content-Length'] / 1024, 2) . ' KB';
        }
    }
    return $sizes;
}

// Definir URL del sitio web a analizar
$siteUrl = "http://localhost/compuIT2";
$pageContent = fetchContent($siteUrl);
$pageLoadTime = getPageLoadTime($siteUrl); // Asignación de $pageLoadTime
$totalWords = $pageContent ? str_word_count(strip_tags($pageContent)) : 0;
$metaTags = $altAttributes = $sizes = [];
$titleContent = $h1Defined = $h2Defined = 'No definido';

// Inicializar variables para palabra clave
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : null;
$keywordCount = $keywordDensity = $keywordInTitle = $keywordInH1 = $keywordInH2 = 0;

// Análisis de etiquetas y estructura SEO
if ($pageContent) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    @$dom->loadHTML($pageContent);
    libxml_clear_errors();

    // Obtener título de la página
    $titleElement = $dom->getElementsByTagName('title')->item(0);
    $titleContent = $titleElement ? $titleElement->nodeValue : 'No definido';

    // Obtener meta descripción y etiquetas Open Graph
    foreach ($dom->getElementsByTagName('meta') as $meta) {
        if ($meta->getAttribute('name') === 'description') {
            $metaTags['description'] = $meta->getAttribute('content') ?: 'No definido';
        }
        if ($meta->getAttribute('property') === 'og:title') {
            $metaTags['og:title'] = $meta->getAttribute('content') ?: 'No definido';
        }
    }

    // Definir encabezados H1, H2 y otros
    $h1Defined = $dom->getElementsByTagName('h1')->length > 0 ? 'Definido' : 'No definido';
    $h2Defined = $dom->getElementsByTagName('h2')->length > 0 ? 'Definido' : 'No definido';
    
    // Obtener atributos ALT de imágenes
    foreach ($dom->getElementsByTagName('img') as $img) {
        if ($img->getAttribute('alt')) {
            $altAttributes[] = $img->getAttribute('alt');
        }
    }
    $sizes = getResourceSizes($siteUrl); // Obtener tamaños de imágenes
}

// Análisis de densidad y relevancia de palabras clave solo si se proporciona la palabra clave
if ($keyword && $pageContent) {
    $pageContentLower = strtolower($pageContent);
    $keywordLower = strtolower($keyword);

    // Contar apariciones de la palabra clave y calcular densidad
    $keywordCount = substr_count($pageContentLower, $keywordLower);
    $keywordDensity = $totalWords > 0 ? round(($keywordCount / $totalWords) * 100, 2) : 0;

    // Verificar presencia en título, H1, y H2
    $keywordInTitle = stripos($titleContent, $keyword) !== false;
    
    foreach (['h1', 'h2'] as $tag) {
        $elements = $dom->getElementsByTagName($tag);
        foreach ($elements as $element) {
            if (stripos($element->textContent, $keyword) !== false) {
                ${"keywordIn" . strtoupper($tag)} = true;
                break;
            }
        }
    }
}

// Obtener resultados de verificación adicionales
$linkCounts = getLinkCounts($siteUrl);
$isHttps = checkHttps($siteUrl) ? 'Sí' : 'No';
$hasSitemap = checkSitemap($siteUrl) ? 'Sí' : 'No';

// Calcular el nivel de cumplimiento de buenas prácticas SEO
$seoScore = 0;
$seoScore += $pageLoadTime < 1000 ? 20 : 10;
$seoScore += $isHttps === 'Sí' ? 20 : 0;
$seoScore += $hasSitemap === 'Sí' ? 20 : 0;
$seoScore += count($altAttributes) > 0 ? 20 : 0;
$seoScore += isFriendlyUrl(parse_url($siteUrl, PHP_URL_PATH)) ? 20 : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control SEO</title>
    <link rel="stylesheet" href="tiendaCSS/panel_seo.css">
    <?php include('includes/header.php'); ?>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
    <header>
        <h1>Panel de Control SEO - Compu IT Marketing</h1>
    </header>
    <main>
        <!-- Métricas SEO On-Page -->
        <section>
            <h2>Métricas SEO On-Page</h2>
            <p><strong>Título de la página:</strong> <?= htmlspecialchars($titleContent); ?></p>
            <p><strong>Meta descripción:</strong> <?= htmlspecialchars($metaTags['description'] ?? 'No definida'); ?></p>
            <p><strong>Encabezados H1:</strong> <?= $h1Defined; ?></p>
            <p><strong>Encabezados H2:</strong> <?= $h2Defined; ?></p>
            <p><strong>Etiquetas Alt en Imágenes:</strong> <?= count($altAttributes) > 0 ? 'Presentes' : 'No presentes'; ?></p>
            <p><strong>Tamaño de Imágenes:</strong> <?= implode(', ', $sizes); ?></p>
        </section>

        <!-- Cumplimiento SEO -->
        <section>
            <h2>Cumplimiento de Buenas Prácticas SEO</h2>
            <label for="seoProgress">Cumplimiento:</label>
            <progress id="seoProgress" value="<?= $seoScore; ?>" max="100"></progress>
            <p>Tu sitio cumple el <?= $seoScore; ?>% de las buenas prácticas SEO</p>
        </section>

        <!-- Análisis de Palabras Clave -->
        <section>
            <h2>Palabras Clave y Densidad</h2>
            <form method="POST">
                <label for="keyword">Palabra clave:</label>
                <input type="text" id="keyword" name="keyword" required>
                <button type="submit">Analizar</button>
            </form>

            <?php if ($keyword): ?>
                <p><strong>Palabra clave analizada:</strong> <?= htmlspecialchars($keyword); ?></p>
                <p><strong>Apariciones de la palabra clave:</strong> <?= $keywordCount; ?></p>
                <p><strong>Densidad de la palabra clave:</strong> <?= $keywordDensity; ?>%</p>
                <p><strong>Presente en título:</strong> <?= $keywordInTitle ? '✅ Sí' : '⚠️ No'; ?></p>
                <p><strong>Presente en H1:</strong> <?= $keywordInH1 ? '✅ Sí' : '⚠️ No'; ?></p>
                <p><strong>Presente en H2:</strong> <?= $keywordInH2 ? '✅ Sí' : '⚠️ No'; ?></p>
            <?php endif; ?>
        </section>

        <!-- URLs Amigables -->
        <section>
            <h2>URLs Amigables</h2>
            <p><strong>URL:</strong> <?= htmlspecialchars($siteUrl); ?></p>
            <p><strong>Es amigable:</strong> <?= isFriendlyUrl(parse_url($siteUrl, PHP_URL_PATH)) ? 'Sí' : 'No'; ?></p>
        </section>

        <!-- Enlaces Internos y Externos -->
        <section>
            <h2>Enlaces Internos y Externos</h2>
            <p><strong>Enlaces Internos:</strong> <?= $linkCounts['internal']; ?></p>
            <p><strong>Enlaces Externos:</strong> <?= $linkCounts['external']; ?></p>
        </section>

        <!-- Seguridad HTTPS -->
        <section>
            <h2>Seguridad HTTPS</h2>
            <p><strong>¿Usa HTTPS?:</strong> <?= $isHttps; ?></p>
        </section>

        <!-- Sitemap.xml -->
        <section>
            <h2>Sitemap.xml</h2>
            <p><strong>¿Tiene Sitemap.xml?:</strong> <?= $hasSitemap; ?></p>
        </section>
    </main>
    <?php include('includes/footer.php'); ?>
</body>
</html>
