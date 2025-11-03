<?php
require 'conexion.php';
?>

<div class="slider">
    <div class="slides">
        <?php
        $query = "SELECT * FROM juegos WHERE tipoProducto = 'preventa'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($productos) > 0) {
            foreach ($productos as $row) {
                echo '
                <div class="card card-preventa">
                    <a href="producto.php?id=' . $row['id'] . '">
                        <img src="img/' . htmlspecialchars($row['imagen']) . '" alt="' . htmlspecialchars($row['nombre']) . '">
                        <h3>' . htmlspecialchars($row['nombre']) . '</h3>
                        <p class="precio">$' . number_format($row['precio'], 2) . ' MXN</p>
                    </a>
                    <div class="acciones">
                        <a href="producto.php?id=' . $row['id'] . '" class="btn-comprar">Reservar</a>
                        <a href="carrito.php?add=' . $row['id'] . '" class="btn-carrito">Agregar al carrito</a>
                    </div>
                </div>';
            }
        } else {
            echo "<p>No hay preventas disponibles en este momento.</p>";
        }

        $conn = null;
        ?>
    </div>
</div>
