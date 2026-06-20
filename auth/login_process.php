<?php

require_once '../config/app.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT *
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        die("Invalid email or password");
    }

    session_regenerate_id(true);

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'admin') {
    header("Location: ../admin/admin_dashboard.php");
    exit;
}

/* ALL normal users go to HOME */
header("Location: ../index.php");
exit;
}
?>
