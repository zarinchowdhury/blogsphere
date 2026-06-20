<?php
require_once 'config/app.php';

$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

try {

    /* GET CATEGORY */
    $stmt = $conn->prepare("
        SELECT *
        FROM categories
        WHERE slug = ?
        LIMIT 1
    ");
    $stmt->execute([$slug]);

    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        http_response_code(404);
        die("Category not found.");
    }

    /* PAGINATION */
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 9;
    $offset = ($page - 1) * $limit;

    /* COUNT POSTS */
    $countStmt = $conn->prepare("
        SELECT COUNT(*)
        FROM posts
        WHERE category_id = ?
        AND status = 'published'
    ");
    $countStmt->execute([$category['category_id']]);

    $totalPosts = (int)$countStmt->fetchColumn();
    $totalPages = max(1, ceil($totalPosts / $limit));

    /* FETCH POSTS */
    $stmt = $conn->prepare("
        SELECT
            p.post_id,
            p.title,
            p.slug,
            p.description,
            p.content,
            p.image_url,
            p.views,
            p.created_at,
            u.username
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.user_id
        WHERE p.category_id = ?
        AND p.status = 'published'
        ORDER BY p.created_at DESC
        LIMIT $limit OFFSET $offset
    ");
    $stmt->execute([$category['category_id']]);

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($category['name']) ?> | BlogSphere</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">

    <a href="<?= BASE_URL ?>index.php"
       class="text-2xl font-bold text-blue-600">
        BlogSphere
    </a>

    <a href="<?= BASE_URL ?>index.php"
       class="text-gray-600 hover:text-blue-600">
        ← Back Home
    </a>

</nav>

<!-- HERO -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">

<div class="max-w-6xl mx-auto px-4">

    <p class="text-sm mb-3 text-blue-100">
        Home / Category / <?= htmlspecialchars($category['name']) ?>
    </p>

    <h1 class="text-4xl font-bold">
        <?= htmlspecialchars($category['name']) ?>
    </h1>

    <?php if (!empty($category['description'])): ?>
        <p class="mt-4 max-w-2xl text-blue-100">
            <?= htmlspecialchars($category['description']) ?>
        </p>
    <?php endif; ?>

    <p class="mt-4 font-medium">
        <?= $totalPosts ?> Published Article(s)
    </p>

</div>

</section>

<!-- POSTS -->
<section class="max-w-6xl mx-auto py-12 px-4">

<?php if (!empty($posts)): ?>

<div class="grid md:grid-cols-3 gap-8">

    <?php foreach ($posts as $post): ?>

        <?php
        $image = !empty($post['image_url'])
            ? $post['image_url']
            : 'https://via.placeholder.com/600x400?text=BlogSphere';
        ?>

        <a href="/BlogSphere/post.php?slug=<?= urlencode($post['slug']) ?>"
   class="block bg-white rounded-xl shadow hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

    <img
        src="<?= htmlspecialchars($image) ?>"
        alt="<?= htmlspecialchars($post['title']) ?>"
        class="w-full h-52 object-cover"
    >

    <div class="p-5">

        <h2 class="font-bold text-xl mb-2 hover:text-blue-600">
            <?= htmlspecialchars($post['title']) ?>
        </h2>

        <div class="text-sm text-gray-500 mb-3">

            By
            <span class="font-medium">
                <?= htmlspecialchars($post['username'] ?? 'Unknown') ?>
            </span>

            •

            <?= date('M d, Y', strtotime($post['created_at'])) ?>

        </div>

        <p class="text-gray-600">

            <?= htmlspecialchars(
                substr(
                    strip_tags(
                        $post['description'] ?: $post['content']
                    ),
                    0,
                    120
                )
            ) ?>...

        </p>

        <div class="flex justify-between items-center mt-4">

            <span class="text-blue-600 font-semibold">
                Read More →
            </span>

            <span class="text-xs text-gray-400">
                👁 <?= (int)$post['views'] ?>
            </span>

        </div>

    </div>

</a>

    <?php endforeach; ?>

</div>

<!-- PAGINATION -->
<?php if ($totalPages > 1): ?>
<div class="flex justify-center mt-10 gap-2">

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>

        <a href="?slug=<?= urlencode($slug) ?>&page=<?= $i ?>"
           class="px-4 py-2 rounded border
           <?= ($i == $page)
                ? 'bg-blue-600 text-white'
                : 'bg-white hover:bg-gray-100'
           ?>">
            <?= $i ?>
        </a>

    <?php endfor; ?>

</div>
<?php endif; ?>

<?php else: ?>

<div class="bg-white rounded-xl shadow p-10 text-center">

    <h2 class="text-2xl font-bold mb-3">
        No Posts Found
    </h2>

    <p class="text-gray-600 mb-5">
        There are currently no published articles in
        <?= htmlspecialchars($category['name']) ?>.
    </p>

    <a href="<?= BASE_URL ?>index.php"
       class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
        Back Home
    </a>

</div>

<?php endif; ?>

</section>

<!-- FOOTER -->
<footer class="bg-white border-t py-6 text-center text-gray-500">
    © <?= date('Y') ?> BlogSphere. All rights reserved.
</footer>

</body>
</html>