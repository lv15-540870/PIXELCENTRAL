<?php
require 'conexion.php';
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Producto no encontrado.";
    exit;
}

$id = intval($_GET['id']);
$query = $conn->prepare("SELECT * FROM juegos WHERE id = ?");
$query->execute([$id]);
$producto = $query->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($producto['nombre']); ?></title>
<link rel="stylesheet" href="producto.css">
</head>
<body>

<div class="producto-container">

    <h1 class="producto-titulo"><?php echo htmlspecialchars($producto['nombre']); ?></h1>

    <div class="producto-detalle">
        <div class="producto-img">
            <img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
        </div>

        <div class="producto-info">
            <p class="plataforma"><strong>Plataforma:</strong> <?php echo htmlspecialchars($producto['plataforma']); ?></p>
            <p class="precio">$<?php echo number_format($producto['precio'], 2); ?> MXN</p>
            <p class="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></p>

            <!-- Formulario Agregar al Carrito -->
            <form method="post" action="carrito.php">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                <button type="submit" class="btn-agregar-carrito">Agregar al carrito</button>
            </form>

            <!-- Botón Ir a Carrito -->
            <a href="carrito.php" class="btn-ir-carrito">Ir a Carrito</a>

            <!-- Botón Pagar con PayPal -->
            <form action="paypal_pago.php" method="post">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
                <button type="submit" class="btn-pagar">Pagar con PayPal</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
