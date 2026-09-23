<?php
require_once '../config/app.php';
require_once '../includes/admin_guard.php';

$id = $_GET['id'];

$stmt = $conn->prepare("
    UPDATE posts
    SET status='published',
        approval_status='approved'
    WHERE post_id=?
");

$stmt->execute([$id]);

header("Location: admin_dashboard.php");
exit;