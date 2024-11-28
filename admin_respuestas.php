<?php
// Iniciar sesión si no está iniciada
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php'); // Redirigir si no es admin
    exit();
}

// Conexión a la base de datos
$servername = "localhost";  // El servidor donde corre MySQL
$username = "compuit";      // El usuario de MySQL
$password = "compuit123";   // La contraseña de MySQL
$dbname = "compuit_db";     // Nombre de la base de datos

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Conexión fallida: ' . $e->getMessage();
    exit();
}

// Procesar eliminación si se envía una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = $_POST['id'] ?? null;
    $tabla = $_POST['tabla'] ?? null;

    if ($id && $tabla) {
        // Validar que la tabla sea permitida
        $tablasPermitidas = ['contactanos', 'unete'];
        if (in_array($tabla, $tablasPermitidas)) {
            try {
                // Preparar y ejecutar la consulta de eliminación
                $stmt = $pdo->prepare("DELETE FROM $tabla WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $mensaje = "Registro eliminado correctamente.";
            } catch (PDOException $e) {
                $error = "Error al eliminar: " . $e->getMessage();
            }
        } else {
            $error = "Tabla no permitida.";
        }
    } else {
        $error = "Datos inválidos.";
    }
}

// Procesar edición si se envía una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'] ?? null;
    $tabla = $_POST['tabla'] ?? null;
    $nuevoNombre = $_POST['nombre'] ?? null;
    $nuevoApellido = $_POST['apellido'] ?? null;

    if ($id && $tabla && $nuevoNombre && in_array($tabla, ['contactanos', 'unete'])) {
        try {
            // Actualizar los datos en la tabla
            $stmt = $pdo->prepare("UPDATE $tabla SET nombre = :nombre, apellido = :apellido WHERE id = :id");
            $stmt->bindParam(':nombre', $nuevoNombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $nuevoApellido, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $mensaje = "Registro actualizado correctamente.";
        } catch (PDOException $e) {
            $error = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        $error = "Datos inválidos para la edición.";
    }
}

// Obtener los datos de las tablas 'contactanos' y 'unete'
$queryContactos = "SELECT * FROM contactanos";
$queryUnete = "SELECT * FROM unete";
$stmtContactos = $pdo->query($queryContactos);
$stmtUnete = $pdo->query($queryUnete);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuestas de Formularios - Admin</title>
    <link rel="stylesheet" href="css/admin_respuestas.css">
</head>
<body>

<!-- Incluir el encabezado -->
<?php include('includes/header.php'); ?>

<div class="container">
    <div class="sidebar">
        <!-- Incluir el sidebar -->
        <?php include('includes/sidebar.php'); ?>
    </div>

    <div class="main-content">
        <h1>Respuestas de Formularios</h1>

        <!-- Mostrar mensajes -->
        <?php if (isset($mensaje)): ?>
            <p style="color: green; text-align: center;"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Tabla de Contactos -->
        <h2>Respuestas de Contacto</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Empresa</th>
                    <th>Problema</th>
                    <th>Fecha</th>
                    <th>Acción</th> <!-- Columna para botones -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmtContactos->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($row['empresa']); ?></td>
                        <td><?php echo htmlspecialchars($row['problema']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                        <td>
                            <!-- Botón de Eliminar -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="tabla" value="contactanos">
                                <button type="submit" name="eliminar" style="background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">
                                    Eliminar
                                </button>
                            </form>
                            <!-- Botón de Editar -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="tabla" value="contactanos">
                                <input type="text" name="nombre" placeholder="Nuevo Nombre" required>
                                <input type="text" name="apellido" placeholder="Nuevo Apellido" required>
                                <button type="submit" name="editar" style="background-color: orange; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">
                                    Editar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Incluir el pie de página -->
<?php include('includes/footer.php'); ?>

</body>
</html>
