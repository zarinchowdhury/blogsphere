<?php

require_once '../config/app.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Whitelist the role — never trust raw POST data for something this sensitive
    if (!in_array($role, ['user', 'admin'], true)) {
        $role = 'user';
    }

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=" . urlencode("All fields are required"));
        exit;
    }

    $stmt = $conn->prepare("
        SELECT user_id
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        header("Location: register.php?error=" . urlencode("Email already exists"));
        exit;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("
        INSERT INTO users
        (
            username,
            email,
            password,
            role
        )
        VALUES
        (
            ?, ?, ?, ?
        )
    ");

    $stmt->execute([
        $username,
        $email,
        $hash,
        $role
    ]);

    header("Location: login.php?registered=1");
    exit;
}
?>