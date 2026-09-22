<?php
require_once '../config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("
    SELECT p.*, c.name AS category_name
    FROM posts p
    LEFT JOIN categories c
        ON p.category_id = c.category_id
    WHERE p.post_id = ?
    AND p.author_id = ?
");

$stmt->execute([
    $id,
    $_SESSION['user_id']
]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

$image = !empty($post['image_url'])
    ? $post['image_url']
    : 'https://via.placeholder.com/1200x500?text=BlogSphere';
?>
<!DOCTYPE html>
<html>
<head>
<title>My Post Preview</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-4xl mx-auto py-10">

    <a href="user_dashboard.php"
       class="text-blue-600 hover:underline">
       ← Back to Dashboard
    </a>

    <div class="bg-white rounded-xl shadow mt-4 overflow-hidden">

        <img src="<?= htmlspecialchars($image) ?>"
             class="w-full h-80 object-cover">

        <div class="p-8">

            <div class="mb-4">

                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm">
                    <?= htmlspecialchars($post['category_name']) ?>
                </span>

            </div>

            <h1 class="text-3xl font-bold mb-4">
                <?= htmlspecialchars($post['title']) ?>
            </h1>

            <div class="text-gray-500 mb-6">

                Created:
                <?= date('M d, Y h:i A', strtotime($post['created_at'])) ?>

            </div>

            <div class="prose max-w-none">

                <?= nl2br(htmlspecialchars($post['content'])) ?>

            </div>

        </div>

    </div>

</div>

</body>
</html>