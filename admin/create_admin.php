<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';


// Replace with your desired admin credentials
$username = 'admin@knightx.com';
$password = 'adminX'; // Change this to a strong password

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$conn = getDBConnection();

$query = "INSERT INTO users (username, password, role) VALUES (:username, :password, 'admin')";
$stmt = $conn->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);

if ($stmt->execute()) {
    echo "Admin user created successfully. Username: $username, Password: $password";
} else {
    echo "Failed to create admin user.";
}
?>
