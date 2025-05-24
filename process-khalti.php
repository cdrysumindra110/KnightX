<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['order_id'])) {
    header('Location: checkout.php');
    exit;
}

$order_id = $_SESSION['order_id'];
$order = getOrder($order_id);

if (!$order) {
    header('Location: checkout.php');
    exit;
}

// Khalti Configuration
$khalti_public_key = getSetting('khalti_public_key');
$khalti_secret_key = getSetting('khalti_secret_key');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Khalti Payment</title>
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
</head>
<body>
    <script>
        var config = {
            "publicKey": "<?php echo $khalti_public_key; ?>",
            "productIdentity": "ORDER-<?php echo $order_id; ?>",
            "productName": "Order #<?php echo $order_id; ?>",
            "productUrl": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/order-details.php?id=' . $order_id; ?>",
            "paymentPreference": [
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT"
            ],
            "eventHandler": {
                onSuccess: function(payload) {
                    // Handle success
                    window.location.href = 'payment-success.php?payment_id=' + payload.idx;
                },
                onError: function(error) {
                    // Handle error
                    window.location.href = 'payment-failure.php?error=' + error.message;
                },
                onClose: function() {
                    // Handle close
                    window.location.href = 'checkout.php';
                }
            }
        };

        var checkout = new KhaltiCheckout(config);
        checkout.show({amount: <?php echo $order['total_amount'] * 100; ?>}); // Amount in paisa
    </script>
</body>
</html> 