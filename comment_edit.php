<?php
require_once 'config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

$stmt = $conn->prepare("SELECT * FROM comments WHERE comment_id = ?");
$stmt->execute([$id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    die("Comment not found");
}

/* AUTH CHECK */
if ($_SESSION['user_id'] != $comment['user_id'] && ($_SESSION['role'] ?? '') !== 'admin') {
    die("Unauthorized");
}

/* UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        UPDATE comments
        SET comment = ?
        WHERE comment_id = ?
    ");

    $stmt->execute([
        $_POST['comment'],
        $id
    ]);

    header("Location: post.php?slug=" . $_POST['slug']);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Comment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-20 bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Edit Comment</h2>

    <form method="POST">

        <input type="hidden" name="slug" value="<?= $_GET['slug'] ?? '' ?>">

        <textarea name="comment"
                  class="w-full border p-3 rounded"
                  required><?= htmlspecialchars($comment['comment']) ?></textarea>

        <button class="mt-3 bg-blue-600 text-white px-4 py-2 rounded">
            Update Comment
        </button>

    </form>

</div>

</body>
</html>