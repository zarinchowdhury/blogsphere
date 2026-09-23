<?php
require_once '../config/app.php';
require_once '../includes/admin_guard.php';

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT
        p.*,
        u.username,
        c.name AS category_name
    FROM posts p
    LEFT JOIN users u ON p.author_id = u.user_id
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.post_id = ?
");

$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found");
}

$image = !empty($post['image_url'])
    ? $post['image_url']
    : 'https://via.placeholder.com/1000x500?text=No+Image';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preview Post | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- TOP BAR -->
<div class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="font-bold text-lg">Post Preview</h1>

    <a href="admin_dashboard.php"
       class="text-blue-600 hover:underline">
        ← Back Dashboard
    </a>
</div>

<div class="max-w-4xl mx-auto py-10">

    <!-- IMAGE -->
    <img src="<?= htmlspecialchars($image) ?>"
         class="w-full h-80 object-cover rounded-xl shadow">

    <!-- CONTENT -->
    <div class="bg-white p-6 mt-6 rounded-xl shadow">

        <span class="text-sm text-blue-600 font-semibold">
            <?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?>
        </span>

        <h1 class="text-3xl font-bold mt-2">
            <?= htmlspecialchars($post['title']) ?>
        </h1>

        <p class="text-gray-500 mt-2">
            By <?= htmlspecialchars($post['username'] ?? 'Unknown') ?>
            • <?= date('M d, Y', strtotime($post['created_at'])) ?>
        </p>

        <hr class="my-4">

        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </p>

        <!-- ACTION BUTTONS -->
        <div class="flex gap-3 mt-6">

            <a href="approve_post.php?id=<?= $post['post_id'] ?>"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Approve
            </a>

            <a href="reject_post.php?id=<?= $post['post_id'] ?>"
               class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
               onclick="return confirm('Reject this post?')">
                Reject
            </a>

        </div>

    </div>

</div>

</body>
</html>