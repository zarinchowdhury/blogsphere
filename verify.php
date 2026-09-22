<?php
require_once '../config/app.php';

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("
    SELECT * FROM users 
    WHERE verification_token=? 
    AND token_expiry > NOW()
");

$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Invalid or expired token");
}

$stmt = $conn->prepare("
    UPDATE users 
    SET is_verified=1, verification_token=NULL, token_expiry=NULL
    WHERE user_id=?
");

$stmt->execute([$user['user_id']]);

echo "Email verified successfully. <a href='login.php'>Login</a>";