<?php
require_once '../config/app.php';
require_once '../includes/admin_guard.php';

$posts = $conn->query("
    SELECT p.*, u.username
    FROM posts p
    JOIN users u ON p.author_id = u.user_id
    WHERE p.approval_status = 'pending'
")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['approve'])) {
    $id = $_GET['approve'];

    $stmt = $conn->prepare("
        UPDATE posts
        SET approval_status='approved', status='published'
        WHERE post_id=?
    ");
    $stmt->execute([$id]);

    header("Location: pending_posts.php");
    exit;
}
?>