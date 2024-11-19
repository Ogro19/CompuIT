<?php
// Solo iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Ecommerce</title>
    <link rel="stylesheet" href="tiendaCSS/theader.css?v=1.1"> <!-- Archivo CSS para los estilos -->
</head>
<body>
    <header>
        <div class="header-content"> <!-- Contenedor principal para organizar los elementos -->
            
            <!-- Logo -->
            <div class="logo-container">
                <a href="index.php">
                    <img src="img/logo.jpg" alt="Compu IT Marketing Logo" class="logo">
                </a>
            </div>

            <!-- Contenedor de ubicación -->
            <div class="location-container">
                <span>Entrega en</span>
                <a href="#" id="location-update" onclick="openLocationPopup()">
                    <?php echo isset($_SESSION['direccion_entrega']) ? htmlspecialchars($_SESSION['direccion_entrega']) : 'Actualizar ubicación'; ?>
                </a>
                <?php if (isset($_SESSION['direccion_entrega'])): ?>
                    <p>No es tu dirección? <button onclick="openLocationPopup()" class="update-button">Actualizar dirección</button></p>
                <?php endif; ?>
            </div>

            <!-- Selector de categoría -->
            <div class="category-container">
                <select name="category" class="category-selector">
                    <option value="productos">Productos</option>
                    <option value="servicios">Servicios</option>
                </select>
            </div>

            <!-- Barra de búsqueda -->
            <div class="search-container">
                <form action="ecommerce.php" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Buscar en Compu IT Marketing...">
                    <button type="submit" class="search-button">Enviar</button>
                </form>
            </div>

            <!-- Icono de carrito con mini carrito -->
            <div class="cart-container" onmouseover="showMiniCart()" onmouseleave="hideMiniCart()">
                <a href="ecart.php" class="cart-link">
                    <img src="img2/bienes.png" alt="Carrito" class="cart-icon">
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
                </a>
                <!-- Mini carrito desplegable -->
                <div class="mini-cart" id="mini-cart">
                    <h4>Tu carrito</h4>
                    <div id="mini-cart-content">
                        <!-- Contenido del mini carrito cargado dinámicamente -->
                    </div>
                    <a href="ecart.php" class="view-cart-button">Ver carrito completo</a>
                </div>
            </div>

            <!-- Menú de usuario -->
            <div class="user-menu" onmouseover="showUserMenu()" onmouseleave="hideUserMenu()">
                <?php
                if (isset($_SESSION['username'])) {
                    // Mostrar imagen de perfil y nombre del usuario
                    $profileImage = $_SESSION['profileimage'] ?? '/compuIT2/uploads/default-profile.png';
                    $username = $_SESSION['username'];
                    echo '<a href="#" class="user-link">';
                    echo '<img src="' . htmlspecialchars($profileImage) . '" alt="Perfil" class="user-icon">'; // Imagen de perfil del usuario
                    echo '<span class="username">' . htmlspecialchars($username) . '</span>';
                    echo '</a>';
                } else {
                    // Si el usuario no ha iniciado sesión, mostrar un enlace de inicio de sesión
                    echo '<a href="/compuIT2/Login.php" class="login-link">Iniciar Sesión</a>';
                }
                ?>
                <div class="user-dropdown" id="user-dropdown">
                    <a href="perfil.php">Perfil</a>
                    <a href="historial_compras.php">Historial de Compras</a>
                    <a href="logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal para ingresar la dirección -->
    <div id="location-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLocationPopup()">&times;</span>
            <h2>Ingresar Dirección de Entrega</h2>
            <form action="save_address.php" method="POST">
                <label for="address">Calle y número:</label>
                <input type="text" id="address" name="address" placeholder="Escribe tu calle y número..." required value="<?php echo isset($_SESSION['direccion_entrega']) ? htmlspecialchars(explode(', ', $_SESSION['direccion_entrega'])[0]) : ''; ?>">
                
                <label for="city">Ciudad:</label>
                <input type="text" id="city" name="city" placeholder="Escribe tu ciudad..." required value="<?php echo isset($_SESSION['direccion_entrega']) ? htmlspecialchars(explode(', ', $_SESSION['direccion_entrega'])[1]) : ''; ?>">
                
                <label for="zip">Código Postal:</label>
                <input type="text" id="zip" name="zip" placeholder="Escribe tu código postal..." required value="<?php echo isset($_SESSION['direccion_entrega']) ? htmlspecialchars(explode(', ', $_SESSION['direccion_entrega'])[2]) : ''; ?>">

                <button type="submit" class="submit-button">Guardar Dirección</button>
            </form>
        </div>
    </div>

    <script>
        // Abre el popup de la ubicación
        function openLocationPopup() {
            document.getElementById("location-modal").style.display = "block";
        }

        // Cierra el popup de la ubicación
        function closeLocationPopup() {
            document.getElementById("location-modal").style.display = "none";
        }

        // Muestra y oculta el mini carrito
        function showMiniCart() {
            document.getElementById("mini-cart").style.display = "block";
            loadMiniCartContent();
        }

        function hideMiniCart() {
            document.getElementById("mini-cart").style.display = "none";
        }

        // Muestra y oculta el menú de usuario
        function showUserMenu() {
            document.getElementById("user-dropdown").style.display = "block";
        }

        function hideUserMenu() {
            document.getElementById("user-dropdown").style.display = "none";
        }

        // Carga el contenido del mini carrito dinámicamente usando AJAX
        function loadMiniCartContent() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "mini_cart.php", true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById("mini-cart-content").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
