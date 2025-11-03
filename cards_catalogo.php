<?php
require 'conexion.php';
?>

<div class="slider">
    <div class="slides">
        <?php
        $query = "SELECT * FROM juegos WHERE tipoProducto = 'catalogo'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($productos) > 0) {
            foreach ($productos as $row):
        ?>
                <div class="card card-catalogo">
                    <a href="producto.php?id=<?php echo $row['id']; ?>">
                        <img src="img/<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                        <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
                        <p class="precio">$<?php echo number_format($row['precio'], 2); ?> MXN</p>
                    </a>
                    <div class="acciones">
                        <form method="POST" action="carrito.php">
                            <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn-carrito">Agregar al carrito</button>
                        </form>
                        <a href="producto.php?id=<?php echo $row['id']; ?>" class="btn-comprar">Comprar</a>
                    </div>
                </div>
        <?php
            endforeach;
        } else {
            echo "<p>No hay productos en catálogo disponibles.</p>";
        }
        $conn = null;
        ?>
    </div>
</div>
