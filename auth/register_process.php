<?php

require_once '../config/app.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required");
    }

    $stmt = $conn->prepare("
        SELECT user_id
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        die("Email already exists");
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("
        INSERT INTO users
        (
            username,
            email,
            password_hash,
            role
        )
        VALUES
        (
            ?, ?, ?, 'user'
        )
    ");

    $stmt->execute([
        $username,
        $email,
        $hash
    ]);

    header("Location: login.php");
    exit;
}
?>
