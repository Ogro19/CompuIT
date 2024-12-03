<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Respuestas - Admin</title>
    <link rel="stylesheet" href="css/admin_respuestas.css">
    <script>
        function confirmarAccion(accion) {
            return confirm(`¿Estás seguro de que deseas ${accion} este registro?`);
        }
    </script>
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
        <h1>Panel de Respuestas</h1>
        <p>Gestiona las respuestas recibidas de los formularios de contacto, solicitudes de unión y datos de usuarios.</p>

        <!-- Sección de Usuarios -->
        <div class="card">
            <h2>Usuarios Registrados</h2>
            <p class="subtitle">Consulta la lista de usuarios registrados en la plataforma.</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta de la tabla usuarios
                    $stmtUsuarios = $pdo->query("SELECT id, username, email, password FROM usuarios");
                    while ($row = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['password']); ?></td>
                            <td>
                                <form method="POST" action="modificar.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="tabla" value="usuarios">
                                    <button type="submit" name="modificar" class="edit" onclick="return confirmarAccion('modificar')">Modificar</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="tabla" value="usuarios">
                                    <button type="submit" name="eliminar" class="delete" onclick="return confirmarAccion('eliminar')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Sección de Contactos -->
        <div class="card">
            <h2>Respuestas de Contacto</h2>
            <p class="subtitle">Aquí puedes revisar los mensajes enviados desde el formulario de contacto.</p>
            <table>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

include('conexion.php');
                    // Consulta de la tabla contactanos
                    $stmtContactos = $pdo->query("SELECT id, nombre, apellido, email, telefono, empresa, problema, fecha FROM contactanos");
                    while ($row = $stmtContactos->fetch(PDO::FETCH_ASSOC)) : ?>
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
                                <form method="POST" action="modificar.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="tabla" value="contactanos">
                                    <button type="submit" name="modificar" class="edit" onclick="return confirmarAccion('modificar')">Modificar</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="tabla" value="contactanos">
                                    <button type="submit" name="eliminar" class="delete" onclick="return confirmarAccion('eliminar')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Sección de Únete -->
        <div class="card">
            <h2>Respuestas de Únete</h2>
            <p class="subtitle">Consulta las solicitudes enviadas para formar parte de nuestro equipo.</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Mensaje</th>
                        <th>CV</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta de la tabla unete
                    $stmtUnete = $pdo->query("SELECT id, nombre, email, telefono, mensaje, cv_path, fecha FROM unete");
                    while ($row = $stmtUnete->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                            <td>
                                <?php if (!empty($row['cv_path'])): ?>
                                    <a href="<?php echo htmlspecialchars($row['cv_path']); ?>" target="_blank">Ver CV</a>
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td>
                                <form method="POST" action="modificar.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="tabla" value="unete">
                                    <button type="submit" name="modificar" class="edit" onclick="return confirmarAccion('modificar')">Modificar</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="tabla" value="unete">
                                    <button type="submit" name="eliminar" class="delete" onclick="return confirmarAccion('eliminar')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Incluir el pie de página -->
<?php include('includes/footer.php'); ?>

</body>
</html>
