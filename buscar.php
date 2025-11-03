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
            echo '<div class="resultado-item">';
            echo '<div class="resultado-img">';
            echo '<img src="img/' . htmlspecialchars($juego['imagen']) . '" alt="' . htmlspecialchars($juego['nombre']) . '">';
            echo '</div>';
            echo '<div class="resultado-info">';
            echo '<h3>' . htmlspecialchars($juego['nombre']) . '</h3>';
            echo '<p class="descripcion">' . htmlspecialchars(substr($juego['descripcion'], 0, 120)) . '...</p>';
            echo '<p class="precio">$' . number_format($juego['precio'], 2) . ' MXN</p>';
            echo '<a href="detalle.php?id=' . $juego['id'] . '" class="boton">Ver detalle</a>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="no-resultados">No se encontraron resultados para "<strong>' . htmlspecialchars($q) . '</strong>".</p>';
    }
} catch (PDOException $e) {
    echo "Error en la búsqueda: " . $e->getMessage();
}
?>
