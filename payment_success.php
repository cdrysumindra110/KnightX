<?php
if (isset($_GET['token']) && isset($_GET['amount'])) {
    $token = $_GET['token'];
    $amount = $_GET['amount'];

    $url = "https://khalti.com/api/v2/payment/verify/";

    $args = http_build_query(array(
        'token' => $token,
        'amount' => $amount
    ));

    $headers = array(
        "Authorization: Key 04eb46c5c31442ed978b3d5ef92b471f"  // Replace with your secret key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Set headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status_code == 200) {
        $response_data = json_decode($response, true);
        echo "Payment successful! Transaction ID: " . $response_data['idx'];
        // ✅ Save order to database here
    } else {
        echo "Payment verification failed.";
    }
} else {
    echo "Invalid payment data.";
}
?>
