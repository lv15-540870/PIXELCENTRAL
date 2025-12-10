<?php
header('Content-Type: text/html; charset=utf-8');
require 'conexion.php';

$q = $_GET['q'] ?? '';
$q = trim($q);

if ($q === '') {
    echo '';
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, nombre, precio, descripcion, imagen, plataforma
                            FROM juegos
                            WHERE (nombre LIKE :q OR plataforma LIKE :q)
                              AND cantidadDisponible > 0
                            ORDER BY nombre ASC
                            LIMIT 20");
    $stmt->execute([':q' => "%$q%"]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
        echo '<div class="resultados-busqueda-lista">';
        foreach ($resultados as $juego) {
            $nombreLimpio = htmlspecialchars($juego['nombre']);
            $descripcionCorta = htmlspecialchars(substr($juego['descripcion'], 0, 100));
            $precioFormateado = number_format($juego['precio'], 2);
            $imagenRuta = htmlspecialchars($juego['imagen']);
            $idJuego = (int)$juego['id'];
            
            echo '<div class="resultado-item">';
            echo '<div class="resultado-img">';
            echo "<img src='img/{$imagenRuta}' alt='{$nombreLimpio}'>";
            echo '</div>';
            echo '<div class="resultado-info">';
            echo "<h3>{$nombreLimpio}</h3>";
            echo "<p class='descripcion'>{$descripcionCorta}...</p>";
            echo "<p class='precio'>\${$precioFormateado} MXN</p>";
            
            // CAMBIADO: Ahora redirige a producto.php en lugar de detalle.php
            echo "<a href='producto.php?id={$idJuego}' class='boton comprar'>Ver detalle</a>";
            
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="no-resultados">No se encontraron resultados para "<strong>' . htmlspecialchars($q) . '</strong>".</p>';
    }
} catch (PDOException $e) {
    echo '<p class="no-resultados" style="color: #ff6b6b;">Error en la búsqueda. Intenta nuevamente.</p>';
    error_log("Error búsqueda: " . $e->getMessage());
}
?>