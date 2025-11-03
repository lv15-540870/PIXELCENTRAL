<?php 
session_start();
require 'conexion.php';

// Inicializar carrito
if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

// Agregar producto al carrito desde POST
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])){
    $id = intval($_POST['producto_id']);
    if(isset($_SESSION['carrito'][$id])) $_SESSION['carrito'][$id]['cantidad']++;
    else $_SESSION['carrito'][$id] = ['cantidad'=>1, 'id'=>$id];

    $_SESSION['mensaje_carrito'] = "Producto agregado al carrito.";
    header("Location: carrito.php");
    exit;
}

// Manejar aumentar/disminuir/eliminar
if(isset($_GET['accion'], $_GET['id'])){
    $id = intval($_GET['id']);
    if($_GET['accion'] == "aumentar") $_SESSION['carrito'][$id]['cantidad']++;
    elseif($_GET['accion'] == "disminuir"){
        $_SESSION['carrito'][$id]['cantidad']--;
        if($_SESSION['carrito'][$id]['cantidad']<=0) unset($_SESSION['carrito'][$id]);
    } elseif($_GET['accion'] == "vaciar") unset($_SESSION['carrito'][$id]);
    header("Location: carrito.php");
    exit;
}

// Obtener productos y total
$productos_carrito = [];
$total = 0;
if(!empty($_SESSION['carrito'])){
    $ids = array_column($_SESSION['carrito'],'id');
    $placeholders = implode(',', array_fill(0,count($ids),'?'));
    $stmt = $conn->prepare("SELECT * FROM juegos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $productos_carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($productos_carrito as $prod){
        $cantidad = $_SESSION['carrito'][$prod['id']]['cantidad'];
        $_SESSION['carrito'][$prod['id']]['precio'] = $prod['precio'];
        $_SESSION['carrito'][$prod['id']]['nombre'] = $prod['nombre'];
        $total += $prod['precio'] * $cantidad;
    }
}

$mensaje = $_SESSION['mensaje_carrito'] ?? '';
unset($_SESSION['mensaje_carrito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrito de Compras</title>
<link rel="stylesheet" href="carrito.css">
<script src="https://www.paypal.com/sdk/js?client-id=AUKmTBBwzAKLX0iZenP7Cg1lMPYjbQbQ0orH1ff4uxDK26V-zxpiZjY0jrVmwnAzoeV90JhqTIr4Ev8b&currency=MXN"></script>
</head>
<body>

<h1>Carrito de Compras</h1>
<?php if($mensaje) echo "<p style='color:green'>$mensaje</p>"; ?>

<?php if(empty($productos_carrito)): ?>
    <p>El carrito está vacío.</p>
<?php else: ?>
<table>
<thead>
<tr><th>Producto</th><th>Plataforma</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>Acciones</th></tr>
</thead>
<tbody>
<?php foreach($productos_carrito as $prod): 
    $cantidad = $_SESSION['carrito'][$prod['id']]['cantidad'];
?>
<tr>
<td><?php echo htmlspecialchars($prod['nombre']); ?></td>
<td><?php echo htmlspecialchars($prod['plataforma']); ?></td>
<td>$<?php echo number_format($prod['precio'],2); ?></td>
<td><?php echo $cantidad; ?></td>
<td>$<?php echo number_format($prod['precio']*$cantidad,2); ?></td>
<td>
<a href="carrito.php?accion=aumentar&id=<?php echo $prod['id']; ?>">+</a>
<a href="carrito.php?accion=disminuir&id=<?php echo $prod['id']; ?>">-</a>
<a href="carrito.php?accion=vaciar&id=<?php echo $prod['id']; ?>">Eliminar</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h2>Total: $<?php echo number_format($total,2); ?></h2>

<?php if(!isset($_SESSION['usuario'])): ?>
    <h3>Datos del comprador</h3>
    <form id="datos-comprador" method="POST" action="pagar.php">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="hidden" name="total" value="<?php echo $total; ?>">
    </form>
    <div id="paypal-button-container"></div>
    <script>
    paypal.Buttons({
        createOrder: function(data, actions){
            return actions.order.create({
                purchase_units:[{amount:{value:'<?php echo number_format($total,2,'.',''); ?>'}}]
            });
        },
        onApprove: function(data, actions){
            let form = document.getElementById('datos-comprador');
            form.action = 'pagar.php?orderID=' + data.orderID;
            form.submit();
        }
    }).render('#paypal-button-container');
    </script>
<?php else: ?>
    <div id="paypal-button-container"></div>
    <script>
    paypal.Buttons({
        createOrder: function(data, actions){
            return actions.order.create({
                purchase_units:[{amount:{value:'<?php echo number_format($total,2,'.',''); ?>'}}]
            });
        },
        onApprove: function(data, actions){
            window.location.href='pagar.php?orderID='+data.orderID;
        }
    }).render('#paypal-button-container');
    </script>
<?php endif; ?>

<?php endif; ?>

</body>
</html>
