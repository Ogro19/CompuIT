<?php

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
        $requiredFields = ['nombre', 'apellido', 'telefono', 'email', 'empresa', 'presupuesto', 'facturacion', 'pais', 'url_sitio', 'problema'];
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->errors[] = "El campo $field es requerido.";
            }
        }

        // Validación de email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "El email no es válido.";
        }

        // Validación de URL
        if (!filter_var($data['url_sitio'], FILTER_VALIDATE_URL)) {
            $this->errors[] = "La URL del sitio web no es válida.";
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
                $data['url_sitio'],
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
require_once 'conexion.php';
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
    <link rel="preload" href="css/contactos.css" as="style">
    <link rel="stylesheet" href="css/contactos.css">
    <?php include('includes/header.php'); ?>
</head>
<body>
    <div class="main-content">
        <?php include 'includes/sidebar.php'; ?>

        <main>
            <!-- Sección de Bienvenida -->
            <section class="welcome-section">
                <h1>ESTAMOS AQUÍ PARA TI</h1>
                <p>Queremos escuchar sobre tus necesidades y ayudarte a alcanzar tus objetivos empresariales.</p>
            </section>

            <!-- Contenedor del Formulario -->
            <div class="form-container">
    <h2>Formulario de Contacto</h2>
    <p class="subtitle">Completa el formulario para agendar tu consultoría gratuita y descubre cómo podemos ayudarte a mejorar tu negocio.</p>
    
    <?php if ($controller->isSuccess()): ?>
        <div class="alert alert-success">
            Tu consulta ha sido enviada exitosamente.
        </div>
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

    <form class="form-section" method="POST" novalidate>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" 
                   id="nombre" 
                   name="nombre" 
                   required 
                   placeholder="Tu nombre"
                   value="<?php echo $_POST['nombre'] ?? ''; ?>"
                   pattern="[a-zA-Z\s]{1,50}" 
                   title="Solo se permiten letras y espacios. Máximo 50 caracteres.">
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" 
                   id="apellido" 
                   name="apellido" 
                   required 
                   placeholder="Tu apellido"
                   value="<?php echo $_POST['apellido'] ?? ''; ?>"
                   pattern="[a-zA-Z\s]{1,50}" 
                   title="Solo se permiten letras y espacios. Máximo 50 caracteres.">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="tel" 
                   id="telefono" 
                   name="telefono" 
                   required 
                   placeholder="Tu teléfono"
                   value="<?php echo $_POST['telefono'] ?? ''; ?>"
                   pattern="\d{10,15}" 
                   title="Solo se permiten números con una longitud entre 10 y 15 dígitos.">
        </div>

        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   required 
                   placeholder="tu@email.com"
                   value="<?php echo $_POST['email'] ?? ''; ?>">
        </div>

        <div class="form-group">
            <label for="empresa">Empresa</label>
            <input type="text" 
                   id="empresa" 
                   name="empresa" 
                   required 
                   placeholder="Nombre de tu empresa"
                   value="<?php echo $_POST['empresa'] ?? ''; ?>"
                   pattern="[a-zA-Z0-9\s]{1,50}" 
                   title="Solo se permiten letras, números y espacios. Máximo 50 caracteres.">
        </div>

        <div class="form-group">
            <label for="presupuesto">Presupuesto mensual</label>
            <select id="presupuesto" name="presupuesto" required>
                <option value="">Selecciona el presupuesto</option>
                <?php
                $presupuestos = ['Bajo', 'Medio', 'Alto'];
                foreach ($presupuestos as $p): ?>
                    <option value="<?php echo $p; ?>" 
                        <?php echo (isset($_POST['presupuesto']) && $_POST['presupuesto'] === $p) ? 'selected' : ''; ?>>
                        <?php echo $p; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="facturacion">Facturación actual</label>
            <select id="facturacion" name="facturacion" required>
                <option value="">Selecciona la facturación</option>
                <?php
                $facturaciones = ['Baja', 'Media', 'Alta'];
                foreach ($facturaciones as $f): ?>
                    <option value="<?php echo $f; ?>"
                        <?php echo (isset($_POST['facturacion']) && $_POST['facturacion'] === $f) ? 'selected' : ''; ?>>
                        <?php echo $f; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="pais">País</label>
            <input type="text" 
                   id="pais" 
                   name="pais" 
                   required 
                   placeholder="Tu país"
                   value="<?php echo $_POST['pais'] ?? ''; ?>"
                   pattern="[a-zA-Z\s]{1,50}" 
                   title="Solo se permiten letras y espacios. Máximo 50 caracteres.">
        </div>

        <div class="form-group">
            <label for="url_sitio">URL del sitio web</label>
            <input type="url" 
                   id="url_sitio" 
                   name="url_sitio" 
                   required 
                   placeholder="https://tusitio.com"
                   value="<?php echo $_POST['url_sitio'] ?? ''; ?>">
        </div>

        <div class="form-group">
            <label for="problema">¿Qué problema enfrenta hoy tu empresa?</label>
            <textarea id="problema" 
                      name="problema" 
                      rows="4" 
                      required 
                      placeholder="Cuéntanos sobre tu problema..."><?php echo $_POST['problema'] ?? ''; ?></textarea>
        </div>

        <button type="submit" class="submit-button">¡Agenda tu consultoría gratuita!</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const restrictInput = (element, regex) => {
        element.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(regex, '');
        });
    };

    restrictInput(document.getElementById('nombre'), /[^a-zA-Z\s]/g);
    restrictInput(document.getElementById('apellido'), /[^a-zA-Z\s]/g);
    restrictInput(document.getElementById('telefono'), /[^0-9]/g);
    restrictInput(document.getElementById('empresa'), /[^a-zA-Z0-9\s]/g);
    restrictInput(document.getElementById('pais'), /[^a-zA-Z\s]/g);
});
</script>

    

    <?php include('includes/footer.php'); ?>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('.form-section');
        
        form.addEventListener('submit', (e) => {
            const inputs = form.querySelectorAll('input, select, textarea');
            let isValid = true;

            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
    });
    </script>
</body>
</html>
