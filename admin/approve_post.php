<?php
require_once '../config/app.php';
require_once '../includes/admin_guard.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("
    UPDATE posts
    SET status = 'published'
    WHERE post_id = ?
");

$stmt->execute([$id]);

header("Location: admin_dashboard.php");
exit;