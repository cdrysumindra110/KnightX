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

// eSewa Configuration
$esewa_merchant_id = getSetting('esewa_merchant_id');
$esewa_secret_key = getSetting('esewa_secret_key');
$esewa_success_url = 'https://' . $_SERVER['HTTP_HOST'] . '/payment-success.php';
$esewa_failure_url = 'https://' . $_SERVER['HTTP_HOST'] . '/payment-failure.php';

// Prepare eSewa payment data
$payment_data = [
    'amt' => $order['total_amount'],
    'pdc' => 0, // Product delivery charge
    'psc' => 0, // Product service charge
    'txAmt' => $order['tax'],
    'tAmt' => $order['total_amount'],
    'pid' => 'ORDER-' . $order_id,
    'scd' => $esewa_merchant_id,
    'su' => $esewa_success_url,
    'fu' => $esewa_failure_url
];

// Generate signature
$signature = hash_hmac('sha256', json_encode($payment_data), $esewa_secret_key);
$payment_data['signature'] = $signature;

// Redirect to eSewa payment page
$esewa_url = 'https://uat.esewa.com.np/epay/main';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa...</title>
</head>
<body>
    <form action="<?php echo $esewa_url; ?>" method="POST" id="esewa-form">
        <?php foreach ($payment_data as $key => $value): ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
        <?php endforeach; ?>
    </form>
    <script>
        document.getElementById('esewa-form').submit();
    </script>
</body>
</html> 