<?php
session_start();
require_once 'includes/functions.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="dark-theme">

<main class="container">
    <h1>Your Cart</h1>

    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><img src="assets/images/products/<?php echo $item['image']; ?>" width="50"></td>
                    <td><?php echo formatPrice($item['price']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo formatPrice($subtotal); ?></td>
                    <td><a href="remove_from_cart.php?id=<?php echo $item['id']; ?>">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: <?php echo formatPrice($total); ?></h3>
        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
