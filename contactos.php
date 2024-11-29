<?php

// Habilitar reporte de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Controlador del formulario
$pdo = require_once 'conexion.php';

class ContactFormController {
    private $db;
    private $errors = [];
    private $success = false;

    public function __construct($db) {
        $this->db = $db;
    }

    public function processForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $formData = $this->sanitizeInput($_POST);

        if ($this->validateForm($formData)) {
            $this->saveContact($formData);
        }
    }

    private function sanitizeInput($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = htmlspecialchars(strip_tags(trim($value)));
        }
        return $sanitized;
    }

    private function validateForm($data) {
        // Validación de campos requeridos
        $requiredFields = ['nombre', 'apellido', 'telefono', 'email', 'empresa', 'presupuesto', 'facturacion', 'pais', 'problema'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->errors[] = "El campo $field es requerido.";
            }
        }

        // Validación de email
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "El email no es válido.";
        }

        // Validación de URL (si se proporciona)
        if (!empty($data['url_sitio']) && !filter_var($data['url_sitio'], FILTER_VALIDATE_URL)) {
            $this->errors[] = "La URL del sitio web no es válida.";
        }

        // Validación de longitud de campos
        if (strlen($data['nombre']) > 100) {
            $this->errors[] = "El nombre no debe exceder los 100 caracteres.";
        }
        if (strlen($data['apellido']) > 100) {
            $this->errors[] = "El apellido no debe exceder los 100 caracteres.";
        }
        if (strlen($data['telefono']) > 20) {
            $this->errors[] = "El teléfono no debe exceder los 20 caracteres.";
        }
        if (strlen($data['email']) > 100) {
            $this->errors[] = "El email no debe exceder los 100 caracteres.";
        }
        if (strlen($data['empresa']) > 100) {
            $this->errors[] = "El nombre de la empresa no debe exceder los 100 caracteres.";
        }
        if (strlen($data['pais']) > 50) {
            $this->errors[] = "El país no debe exceder los 50 caracteres.";
        }

        // Validación de ENUM
        $validPresupuestos = ['Bajo', 'Medio', 'Alto'];
        $validFacturaciones = ['Baja', 'Media', 'Alta'];

        if (!in_array($data['presupuesto'], $validPresupuestos)) {
            $this->errors[] = "El valor del presupuesto no es válido.";
        }
        if (!in_array($data['facturacion'], $validFacturaciones)) {
            $this->errors[] = "El valor de la facturación no es válido.";
        }

        return empty($this->errors);
    }

    private function saveContact($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO contactanos (
                    nombre, apellido, telefono, email, empresa, 
                    pais, url_sitio, presupuesto, facturacion, problema
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['nombre'],
                $data['apellido'],
                $data['telefono'],
                $data['email'],
                $data['empresa'],
                $data['pais'],
                !empty($data['url_sitio']) ? $data['url_sitio'] : null,
                $data['presupuesto'],
                $data['facturacion'],
                $data['problema']
            ]);

            $this->success = true;
        } catch (PDOException $e) {
            $this->errors[] = "Error al guardar los datos: " . $e->getMessage();
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function isSuccess() {
        return $this->success;
    }
}

// Inicialización
$controller = new ContactFormController($pdo);
$controller->processForm();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contacta a Compu IT Marketing para recibir consultoría gratuita y soluciones personalizadas en marketing digital.">
    <meta name="keywords" content="contacto, consultoría gratuita, marketing digital, Compu IT Marketing">
    <title>Formulario de Contacto | Compu IT Marketing</title>
    <link rel="stylesheet" href="css/contactos.css">
</head>
<body>
    <div class="form-container">
        <h2>Formulario de Contacto</h2>

        <?php if ($controller->isSuccess()): ?>
            <div class="alert alert-success">Tu consulta ha sido enviada exitosamente.</div>
        <?php endif; ?>

        <?php if (!empty($controller->getErrors())): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($controller->getErrors() as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $_POST['nombre'] ?? ''; ?>" required>
            <input type="text" name="apellido" placeholder="Apellido" value="<?php echo $_POST['apellido'] ?? ''; ?>" required>
            <input type="tel" name="telefono" placeholder="Teléfono" value="<?php echo $_POST['telefono'] ?? ''; ?>" required>
            <input type="email" name="email" placeholder="Correo electrónico" value="<?php echo $_POST['email'] ?? ''; ?>" required>
            <input type="text" name="empresa" placeholder="Empresa" value="<?php echo $_POST['empresa'] ?? ''; ?>" required>
            <input type="text" name="pais" placeholder="País" value="<?php echo $_POST['pais'] ?? ''; ?>" required>
            <input type="url" name="url_sitio" placeholder="URL del sitio web" value="<?php echo $_POST['url_sitio'] ?? ''; ?>">
            <select name="presupuesto" required>
                <option value="">Presupuesto</option>
                <option value="Bajo" <?php echo ($_POST['presupuesto'] ?? '') === 'Bajo' ? 'selected' : ''; ?>>Bajo</option>
                <option value="Medio" <?php echo ($_POST['presupuesto'] ?? '') === 'Medio' ? 'selected' : ''; ?>>Medio</option>
                <option value="Alto" <?php echo ($_POST['presupuesto'] ?? '') === 'Alto' ? 'selected' : ''; ?>>Alto</option>
            </select>
            <select name="facturacion" required>
                <option value="">Facturación</option>
                <option value="Baja" <?php echo ($_POST['facturacion'] ?? '') === 'Baja' ? 'selected' : ''; ?>>Baja</option>
                <option value="Media" <?php echo ($_POST['facturacion'] ?? '') === 'Media' ? 'selected' : ''; ?>>Media</option>
                <option value="Alta" <?php echo ($_POST['facturacion'] ?? '') === 'Alta' ? 'selected' : ''; ?>>Alta</option>
            </select>
            <textarea name="problema" placeholder="¿Qué problema enfrenta tu empresa?" required><?php echo $_POST['problema'] ?? ''; ?></textarea>
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
