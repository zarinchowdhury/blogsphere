<?php
require_once '../config/app.php';
require_once '../includes/admin_guard.php';

<<<<<<< HEAD
$id = $_GET['id'];

$stmt = $conn->prepare("
    UPDATE posts
    SET status='rejected',
        approval_status='rejected'
    WHERE post_id=?
=======
$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("
    UPDATE posts
    SET status = 'rejected'
    WHERE post_id = ?
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9
");

$stmt->execute([$id]);

header("Location: admin_dashboard.php");
exit;