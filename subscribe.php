<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email is required']);
    exit;
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

try {
    if (isSubscribed($email)) {
        echo json_encode(['message' => 'You are already subscribed to our newsletter']);
        exit;
    }

    if (subscribeNewsletter($email)) {
        echo json_encode(['message' => 'Successfully subscribed to newsletter']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to subscribe to newsletter']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while subscribing to newsletter']);
} 