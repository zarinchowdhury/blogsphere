<?php
require_once '../config/app.php';
require_once '../includes/auth_guard.php';
require_once '../config/database.php';
require_once '../auth.php';

// DB connection (IMPORTANT FIX)
$database = new Database();
$conn = $database->connect();

requireLogin();

$user_id = $_SESSION['user_id'];

// user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// user posts
$stmt = $conn->prepare("
    SELECT * FROM posts
    WHERE author_id = ?
    ORDER BY post_id DESC
");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>