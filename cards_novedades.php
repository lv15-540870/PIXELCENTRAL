<?php
require 'conexion.php';

$query = "SELECT * FROM juegos WHERE tipoProducto = 'novedad'";
$stmt = $conn->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($productos) > 0) {
    foreach ($productos as $row) {
        echo '<div class="card">';
        echo '<a href="producto.php?id=' . $row['id'] . '">';
        echo '<img src="img/' . htmlspecialchars($row['imagen']) . '" alt="' . htmlspecialchars($row['nombre']) . '">';
        echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
        echo '<p class="precio">$' . number_format($row['precio'], 2) . ' MXN</p>';
        echo '</a>';
        echo '<div class="acciones">';
        
        // Botón Ver Detalles
        echo '<a href="producto.php?id=' . $row['id'] . '" class="btn-comprar">Ver Detalles</a>';
        
        // FORMULARIO para agregar al carrito
        echo '<form method="POST" action="carrito.php" style="margin: 0; width: 100%;">';
        echo '<input type="hidden" name="producto_id" value="' . $row['id'] . '">';
        echo '<button type="submit" class="btn-carrito">Agregar al carrito</button>';
        echo '</form>';
        
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "<p>No hay novedades disponibles en este momento.</p>";
}

$conn = null;
?>