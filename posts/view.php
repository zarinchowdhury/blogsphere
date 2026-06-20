<?php
require_once '../config/app.php';
require_once '../config/database.php';

$database = new Database();
$conn = $database->connect();

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    die("Invalid post.");
}

/*
|--------------------------------------------------------------------------
| Get Post
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare("
    SELECT
        posts.*,
        users.username,
        categories.name AS category_name
    FROM posts
    JOIN users
        ON users.user_id = posts.author_id
    LEFT JOIN categories
        ON categories.category_id = posts.category_id
    WHERE posts.slug = ?
");

$stmt->execute([$slug]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

/*
|--------------------------------------------------------------------------
| Increase Views
|--------------------------------------------------------------------------
*/
$updateViews = $conn->prepare("
    UPDATE posts
    SET views = COALESCE(views, 0) + 1
    WHERE post_id = ?
");

$updateViews->execute([$post['post_id']]);
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> | BlogSphere</title>

```
<script src="https://cdn.tailwindcss.com"></script>
```

</head>

<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 min-h-screen">

<!-- Navbar -->

<nav class="bg-white shadow-md border-b">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">

```
    <a href="../index.php"
    class="text-2xl font-bold text-blue-600">
        BlogSphere
    </a>

    <a href="../index.php"
    class="text-gray-600 hover:text-blue-600">
        ← Back to Home
    </a>

</div>
```

</nav>

<!-- Article -->

<div class="max-w-4xl mx-auto mt-10">

```
<article class="bg-white rounded-3xl shadow-xl overflow-hidden">

    <?php if (!empty($post['image_url'])): ?>

        <img
            src="../assets/images/<?= htmlspecialchars($post['image_url']) ?>"
            alt="<?= htmlspecialchars($post['title']) ?>"
            class="w-full h-96 object-cover"
        >

    <?php else: ?>

        <img
            src="../assets/images/default-post.jpg"
            alt="Default Post Image"
            class="w-full h-96 object-cover"
        >

    <?php endif; ?>

    <div class="p-8">

        <!-- Category -->
        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
            <?= htmlspecialchars($post['category_name'] ?? 'General') ?>
        </span>

        <!-- Title -->
        <h1 class="text-4xl font-bold mt-4 text-gray-800">
            <?= htmlspecialchars($post['title']) ?>
        </h1>

        <!-- Description -->
        <?php if (!empty($post['description'])): ?>
            <p class="text-lg text-gray-600 mt-4">
                <?= htmlspecialchars($post['description']) ?>
            </p>
        <?php endif; ?>

        <!-- Meta -->
        <div class="mt-6 flex flex-wrap gap-6 text-sm text-gray-500">

            <span>
                Author:
                <?= htmlspecialchars($post['username']) ?>
            </span>

            <span>
                Views:
                <?= (int)$post['views'] + 1 ?>
            </span>

            <span>
                Published:
                <?= date('F j, Y', strtotime($post['created_at'])) ?>
            </span>

        </div>

        <hr class="my-8">

        <!-- Content -->
        <div class="prose max-w-none text-gray-700 leading-relaxed">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>

    </div>

</article>
```

</div>

</body>
</html>
