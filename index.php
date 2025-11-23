<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pixel Central</title>
    <link rel="stylesheet" href="normalize.css" as="style">
    <link rel="stylesheet" href="normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preload" href="styles.css" as="style">
    <link href="styles.css" rel="stylesheet">

    <!--TAREA JS SLIDER CON JQUERY-->
    <script>
    $(document).ready(function(){
        $('.slider-contenido').slick({
            infinite: true,
            slidesToShow: 3,
            autoplay: true,
            autoplaySpeed: 2000
        });
    });
    </script>
</head>
<body>
    <?php
// Mostrar mensaje de compra exitosa y botón de descarga
if(isset($_SESSION['pdf_file'])){
    $pdf_file = $_SESSION['pdf_file'];
    echo "<div style='text-align:center; margin:20px; padding:10px; border:1px solid #28a745; background:#d4edda; border-radius:5px;'>";
    echo "<p style='color:#155724; font-weight:bold;'>Compra realizada con éxito.</p>";
    echo "<a href='$pdf_file' target='_blank' style='padding:10px 20px; background:#28a745; color:#fff; text-decoration:none; border-radius:5px;'>Descargar Recibo PDF</a>";
    echo "</div>";
    unset($_SESSION['pdf_file']);
}
?>

    <!-- INICIO ENCABEZADO -->
    <header>
        <div class="header-superior">
            <h1 class="titulo">PIXEL CENTRAL <span>Consolas, VideoJuegos y Más...</span></h1>
            <div class="user-area">
            <?php if (isset($_SESSION['usuario'])): ?>
                <span class="bienvenida">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?>!</span>
                <a href="cerrar_sesion.php" class="btn-logout">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Iniciar Sesión / Regístrate</a>
            <?php endif; ?>
            </div>
        </div>

        <div class="buscador">
            <input type="text" id="busqueda" placeholder="Busca tus juegos, consolas o accesorios favoritos...">
            <div id="resultados"></div>
        </div>
        
        <!-- TAREA JS LIBRE - ANIMACION BANNER -->
        <script>
        window.addEventListener("load", () => {
            const banner = document.querySelector(".banner-texto h1");
            banner.style.transition = "0.5s";
            banner.style.transform = "scale(1.1)";
            setTimeout(()=> banner.style.transform = "scale(1)", 800);
        });
        </script>


    </header>
    <!-- FIN ENCABEZADO -->

    <!-- INICIO MENU -->
    <header class="encabezado">
        <div class="nav-bg">
            <nav class="navegacion-principal contenedor">
                <a href="#novedades">Novedades</a>
                <a href="#preventas">Preventas</a>
                <a href="#catalogo">Nuestro Catálogo</a>
                <a href="#ubicacion">Ubicación</a>
                <a href="#formulario">Servicio Al Cliente</a>
              <?php
                if (isset($_SESSION['usuario'])) {
                    if ($_SESSION['usuario']['rol'] === 'admin') {
                        echo '<a href="inventario.php">Inventario</a>';
                    } else {
                        echo '<a href="carrito.php">Carrito</a>';
                    }
                } else {
                        echo '<a href="login.php">Carrito</a>';
                }
                ?>
            </nav>
        </div>
        <div class="banner">
            <img src="img/banner.jpg" alt="Banner Pixel Central" class="banner-img">
            <div class="banner-texto">
                <h1>PIXEL CENTRAL VIDEOGAME CENTER</h1>
            </div>
        </div>
    </header>
    <!-- FIN MENU -->
    <!--INICIO APARTADO VIDEOJUEGOS-->
    <main class="contenedor sombra">
        <!--INICIO NOVEDADES-->
        <section id="novedades" class="slider">
            <div class="slider-header">
                    <h2> Novedades</h2>
                    <p>Descubre los títulos más recientes que acaban de llegar a Pixel Central.</p>
            </div>
                    <?php include 'cards_novedades.php'; ?>
                </div>
            </section>
            <!--FIN NOVEDADES-->
            <!--INICIO PREVENTAS-->
            <section id="preventas" class="slider">
                <div class="slider-header">
                    <h2> Preventas </h2>
                    <p>Reserva tus títulos favoritos antes de su lanzamiento y sé de los primeros en jugarlos.</p>
                </div>
                <div class="slider-contenido">
                    <?php include 'cards_preventa.php'; ?>
                </div>
            </section>
            <!--FIN PREVENTAS-->
            <!--INICIO CATALOGO-->
            <section id="catalogo" class="slider">
                <div class="slider-header">
                    <h2>Catálogo</h2>
                    <p>Explora nuestros juegos disponibles para todas las consolas.</p>
                </div>
                <div class="slider-contenido">
                    <?php include 'cards_catalogo.php'; ?>
                </div>
            </section>
        </div>    
        <!--FIN CATALOGO-->
       
        <!--INICIO UBICACION-->
        <section id="ubicacion" class="mapa"> <h2>¡Ubicanos en Plaza de la Tecnología Hidalgo #1334 poniente, Torreón, Mexico, en el local 322 en el segundo piso.</h2> <div class="contenedor-mapa" style="text-align:center; margin-top:20px;"> 
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2527.9559246028207!2d-103.46289904751433!3d25.536972806719735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2smx!4v1759757986514!5m2!1ses-419!2smx" 
            width="600" 
            height="450" 
            style="border:0; border-radius:10px;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade"> 
            </iframe> 
            </div> 
        </section> 
        <!-- FIN UBICACION -->
    </main> 
    <!--FIN APARTADO DE JUEGOS-->
    <!--INICIO SERVICIO AL CLIENTE-->
    <section id="formulario">
        <h2></h2>
        <form id="formContacto" class="formulario" method="post" action="#" onsubmit="return validarFormulario()">
            <fieldset>
                <legend>¿Tienes dudas? ¡Contáctanos enviándonos un correo!</legend>

                <div class="contenedor-campos">

                    <div class="campo">
                        <label>Nombre</label>
                        <input id="nombre" name="nombre" class="input-text" type="text" placeholder="Tu nombre completo">
                        <small class="error"></small>
                    </div>

                    <div class="campo">
                        <label>Teléfono</label>
                        <input id="telefono" name="telefono" class="input-text" type="text" placeholder="Teléfono para contactarte">
                        <small class="error"></small>
                    </div>

                    <div class="campo">
                        <label>Correo Electrónico</label>
                        <input id="correo" name="correo" class="input-text" type="email" placeholder="El correo con el que te contactaremos">
                        <small class="error"></small>
                    </div>

                    <div class="campo">
                        <label>Coméntanos tu duda</label>
                        <textarea id="mensaje" name="mensaje" class="input-text" maxlength="200"></textarea>
                        <small class="error"></small>
                        <p id="contador">0/200</p>
                    </div>

                </div> <!--.contenedor-campos-->    
                
                <div class="alinear-derecha flex">
                    <input class="boton w-sm-100" type="submit" value="Enviar">
                </div>    
            </fieldset>
        </form>
    </section>

    <script>
    // VALIDACIÓN + DOM (3 usos): errores, limpieza, contador

    function validarFormulario(){
        const nombre = document.getElementById("nombre");
        const telefono = document.getElementById("telefono");
        const correo = document.getElementById("correo");
        const mensaje = document.getElementById("mensaje");

        let valido = true;

        //Uso DOM #1: limpiar errores
        document.querySelectorAll("#formContacto .error").forEach(el => el.textContent = "");

        // Validación con regex
        if(!/^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]{3,50}$/.test(nombre.value.trim())){
            nombre.nextElementSibling.textContent = "Nombre inválido (solo letras, mínimo 3 chars).";
            valido = false;
        }

        if(!/^\d{10}$/.test(telefono.value.trim())){
            telefono.nextElementSibling.textContent = "Teléfono inválido (10 dígitos).";
            valido = false;
        }

        if(!/^[\w\.\-]+@[\w\-]+\.[A-Za-z]{2,}$/.test(correo.value.trim())){
            correo.nextElementSibling.textContent = "Correo inválido.";
            valido = false;
        }

        if(mensaje.value.trim().length < 5){
            mensaje.nextElementSibling.textContent = "Mensaje muy corto.";
            valido = false;
        }

        return valido;
    }

    //Uso DOM #2 y #3: contador en vivo + evento input
    const txtMensaje = document.getElementById("mensaje");
    const contador = document.getElementById("contador");

    txtMensaje.addEventListener("input", () => {
        contador.textContent = txtMensaje.value.length + "/200";
    });
    </script>

        <!--FIN SERVICIO AL CLIENTE-->
    <footer class="footer">
        <p>Todos los derechos reservados. Luis Antonio Cuevas Ortiz Freelancer </p>
    </footer>
    
    <!--BUSCADOR-->
    <script>
        document.getElementById("busqueda").addEventListener("keyup", function() {
        let q = this.value.trim();
            if (q.length > 0) {
                fetch("buscar.php?q=" + encodeURIComponent(q))
                    .then(res => res.text())
                    .then(data => {
                        document.getElementById("resultados").innerHTML = data;
                    });
            } else {
                document.getElementById("resultados").innerHTML = "";
            }
        });
    </script>
</body>
</html>
