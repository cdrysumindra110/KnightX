<?php
session_start();
require_once 'includes/functions.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

foreach ($cart as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
}

// Convert to paisa for Khalti (1 NPR = 100 paisa)
$amount_in_paisa = $total * 100;
$productIdentity = bin2hex(random_bytes(10));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - KnightX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://khalti.com/static/khalti-checkout.js"></script>

</head>
<body class="dark-theme">

<main class="container">
    <h1>Checkout</h1>

    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <h3>Total Payable: <?php echo formatPrice($total); ?></h3>
        <button id="khalti-pay-btn" class="btn btn-primary">Pay with Khalti</button>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?><br>

<script>
    var config = {
    "publicKey": "4a6893e250b14e8f8303c4f45b3d1dec",  // replace this
    "productIdentity": "<?php echo $productIdentity; ?>",          // unique ID for the purchase
    "productName": "KnightX Order",
    "productUrl": "http://localhost/E-Commerce/KnightX/",
    "paymentPreference": ["KHALTI", "EBANKING", "MOBILE_BANKING", "CONNECT_IPS"],
    "eventHandler": {
        onSuccess(payload) {
            console.log(payload);
            // Redirect to success page with token
            window.location.href = "payment_success.php?token=" + payload.token + "&amount=" + payload.amount;
        },
        onError(error) {
            console.error(error);
            alert("Payment failed. Please try again.");
        },
        onClose() {
            console.log("Widget is closing");
        }
    }
    };

var checkout = new KhaltiCheckout(config);

/* document.getElementById("khalti-pay-btn").onclick = function () {
    checkout.show({ amount: totalAmountInPaisa }); // e.g. 500 * 100
}; */


    var checkout = new KhaltiCheckout(config);
    document.getElementById("khalti-pay-btn").onclick = function () {
        checkout.show({amount: <?php echo $amount_in_paisa; ?>});
    };
</script>
</body>
</html>
