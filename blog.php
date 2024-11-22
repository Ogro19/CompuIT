<?php
session_start();
error_reporting(error_level: E_ALL);
ini_set('display_errors', 1);

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=compuit_db",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

class VideoManager {
    private $db;
    private $itemsPerPage = 8;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getVideos(string $searchTerm = '', int $page = 1): array {
        $offset = ($page - 1) * $this->itemsPerPage;
        
        try {
            $sql = "SELECT * FROM videos_blog"; // Cambiado a la tabla correcta
            $params = [];

            if (!empty($searchTerm)) {
                $sql .= " WHERE titulo LIKE :search OR resumen LIKE :search"; // Campos correctos
                $params[':search'] = '%' . $searchTerm . '%';
            }

            $sql .= " ORDER BY fecha_publicacion DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }
            
            $stmt->bindValue(':limit', $this->itemsPerPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getVideos: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalPages(string $searchTerm = ''): int {
        try {
            $sql = "SELECT COUNT(*) FROM videos_blog"; // Cambiado a la tabla correcta
            $params = [];

            if (!empty($searchTerm)) {
                $sql .= " WHERE titulo LIKE :search OR resumen LIKE :search"; // Campos correctos
                $params[':search'] = '%' . $searchTerm . '%';
            }

            $stmt = $this->db->prepare($sql);
            
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            $total = $stmt->fetchColumn();
            return ceil($total / $this->itemsPerPage);
        } catch (PDOException $e) {
            error_log("Error en getTotalPages: " . $e->getMessage());
            return 0;
        }
    }

    public function extractYoutubeId(string $url): ?string {
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}

class BlogController {
    private $videoManager;
    private $searchTerm;
    private $page;
    private $videos;
    private $totalPages;

    public function __construct(VideoManager $videoManager) {
        $this->videoManager = $videoManager;
        $this->searchTerm = $_GET['query'] ?? '';
        $this->page = max(1, (int)($_GET['page'] ?? 1));
        $this->loadData();
    }

    private function loadData(): void {
        $this->videos = $this->videoManager->getVideos($this->searchTerm, $this->page);
        $this->totalPages = $this->videoManager->getTotalPages($this->searchTerm);
    }

    public function getVideos(): array {
        return $this->videos;
    }

    public function getTotalPages(): int {
        return $this->totalPages;
    }

    public function getSearchTerm(): string {
        return $this->searchTerm;
    }

    public function getCurrentPage(): int {
        return $this->page;
    }

    public function isAdmin(): bool {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $videoManager = new VideoManager($pdo);
    $controller = new BlogController($videoManager);
} catch (Exception $e) {
    error_log("Error de inicialización: " . $e->getMessage());
    die("Error al cargar la página. Por favor, intente más tarde.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blog de Marketing Digital con las últimas tendencias y estrategias">
    <title>Compu IT Marketing Digital - Blog</title>
    <link rel="preload" href="css/blog.css" as="style">
    <link rel="stylesheet" href="css/blog.css">
    <?php include('includes/header.php'); ?>
</head>
<body>
    <div class="container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <h1>Videos de Marketing Digital</h1>

            <form method="GET" action="blog.php" class="search-form">
                <input type="text" 
                       id="searchQuery" 
                       name="query" 
                       placeholder="Buscar videos..." 
                       value="<?= htmlspecialchars($controller->getSearchTerm()); ?>"
                       maxlength="100">
                <button type="submit">Buscar</button>
            </form>

            <section class="blog-section">
                <?php if (!empty($controller->getVideos())): ?>
                    <?php foreach ($controller->getVideos() as $video): ?>
                        <article class="blog-card">
                            <div class="video-container">
                                <iframe 
                                    width="100%" 
                                    height="200"
                                    src="https://www.youtube.com/embed/<?= $videoManager->extractYoutubeId($video['youtube_link']); ?>" 
                                    title="<?= htmlspecialchars($video['titulo']); ?>"
                                    frameborder="0"
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                            <div class="video-content">
                                <h2><?= htmlspecialchars($video['titulo']); ?></h2>
                                <p><?= htmlspecialchars($video['resumen']); ?></p>
                                <div class="video-meta">
                                    <span class="video-date">
                                        Publicado: <?= date('d/m/Y', strtotime($video['fecha_publicacion'])); ?>
                                    </span>
                                </div>
                                <div class="card-actions">
                                    <a href="<?= htmlspecialchars($video['youtube_link']); ?>" 
                                       target="_blank" 
                                       class="read-more"
                                       rel="noopener">
                                        Ver en YouTube
                                    </a>
                                    <?php if ($controller->isAdmin()): ?>
                                        <a href="editar_video.php?id=<?= $video['id']; ?>" 
                                           class="edit-button">
                                            Editar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-results">No se encontraron videos<?= $controller->getSearchTerm() ? ' para "' . htmlspecialchars($controller->getSearchTerm()) . '"' : ''; ?></p>
                <?php endif; ?>
            </section>

            <?php if ($controller->getTotalPages() > 1): ?>
                <nav class="pagination">
                    <?php for ($i = 1; $i <= $controller->getTotalPages(); $i++): ?>
                        <a href="?query=<?= urlencode($controller->getSearchTerm()); ?>&page=<?= $i; ?>" 
                           class="<?= $i === $controller->getCurrentPage() ? 'active' : ''; ?>"
                           aria-label="Página <?= $i; ?>"
                           <?= $i === $controller->getCurrentPage() ? 'aria-current="page"' : ''; ?>>
                            <?= $i; ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchQuery');
        
        function handleSearch(event) {
            const query = event.target.value.trim();
            if (query.length > 100) {
                alert('La búsqueda no puede exceder los 100 caracteres.');
                searchInput.value = query.substring(0, 100);
            }
        }

        searchInput.addEventListener('input', handleSearch);
    });
    </script>
</body>
</html>