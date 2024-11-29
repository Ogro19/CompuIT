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
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Tabla de Unete -->
        <h2>Respuestas de Únete</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Acción</th> <!-- Columna para botones -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmtUnete->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($row['motivo']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                        <td>
                            <!-- Botón de Eliminar -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="tabla" value="unete">
                                <button type="submit" name="eliminar" style="background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">
                                    Eliminar
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
