<?php
require_once 'config/app.php';

$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    header("Location: index.php");
    exit;
}
// HANDLE COMMENT SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: /BlogSphere/auth/login.php");
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO comments (post_id, user_id, comment, created_at)
        VALUES (?, ?, ?, NOW())
    ");

    $stmt->execute([
        $_POST['post_id'],
        $_SESSION['user_id'],
        $_POST['comment']
    ]);

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

try {

    /* =========================
       GET POST
    ========================= */
    $stmt = $conn->prepare("
        SELECT p.*, u.username,
               c.name AS category_name,
               c.slug AS category_slug
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.user_id
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE p.slug = ?
        LIMIT 1
    ");

    $stmt->execute([$slug]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        http_response_code(404);
        die("Post not found");
    }

    /* =========================
       UPDATE VIEWS
    ========================= */
    $conn->prepare("
        UPDATE posts
        SET views = COALESCE(views,0) + 1
        WHERE post_id = ?
    ")->execute([$post['post_id']]);

    $post['views'] = ($post['views'] ?? 0) + 1;

    /* =========================
       RELATED POSTS
    ========================= */
    $stmt = $conn->prepare("
        SELECT post_id, title, slug, image_url
        FROM posts
        WHERE category_id = ?
        AND post_id != ?
        AND status = 'published'
        ORDER BY created_at DESC
        LIMIT 4
    ");

    $stmt->execute([$post['category_id'], $post['post_id']]);
    $relatedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* =========================
       COMMENTS (ONLY IF PUBLISHED)
    ========================= */
    $comments = [];

    if ($post['status'] === 'published') {

        $stmt = $conn->prepare("
            SELECT c.*, u.username
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.user_id
            WHERE c.post_id = ?
            ORDER BY c.created_at DESC
        ");

        $stmt->execute([$post['post_id']]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($post['title']) ?> | BlogSphere</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- NAV -->
<nav class="bg-white shadow px-6 py-4 flex justify-between">
    <a href="/BlogSphere/index.php" class="font-bold text-blue-600 text-xl">
        BlogSphere
    </a>

    <a href="/BlogSphere/category.php?slug=<?= $post['category_slug'] ?>"
       class="text-gray-600 hover:text-blue-600">
        ← Back to Category
    </a>
</nav>

<!-- HERO -->
<header class="relative">

    <img src="<?= htmlspecialchars($post['image_url'] ?: 'https://via.placeholder.com/1200x500') ?>"
         class="w-full h-[450px] object-cover">

    <div class="absolute bottom-0 bg-black/60 text-white p-6 w-full">

        <a href="/BlogSphere/category.php?slug=<?= $post['category_slug'] ?>"
           class="bg-blue-600 px-3 py-1 text-sm rounded">
            <?= htmlspecialchars($post['category_name']) ?>
        </a>

        <h1 class="text-4xl font-bold mt-3">
            <?= htmlspecialchars($post['title']) ?>
        </h1>

        <div class="text-sm mt-2 text-gray-200">
            By <?= htmlspecialchars($post['username'] ?? 'Unknown') ?>
            • <?= date('M d, Y', strtotime($post['created_at'])) ?>
            • 👁 <?= (int)$post['views'] ?> views
        </div>

    </div>

</header>

<!-- CONTENT -->
<section class="max-w-4xl mx-auto py-10 px-4">

    <div class="bg-white p-6 rounded shadow">

        <p class="text-gray-700 leading-relaxed">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </p>

    </div>

</section>

<!-- RELATED POSTS -->
<section class="max-w-6xl mx-auto py-10 px-4">

    <h2 class="text-2xl font-bold mb-6">Related Posts</h2>

    <div class="grid md:grid-cols-4 gap-6">

        <?php foreach ($relatedPosts as $r): ?>
            <a href="/BlogSphere/post.php?slug=<?= $r['slug'] ?>"
               class="bg-white rounded shadow hover:shadow-lg overflow-hidden">

                <img src="<?= htmlspecialchars($r['image_url']) ?>"
                     class="h-32 w-full object-cover">

                <div class="p-3 text-sm font-semibold">
                    <?= htmlspecialchars($r['title']) ?>
                </div>

            </a>
        <?php endforeach; ?>

    </div>

</section>

<!-- COMMENTS SECTION -->
<?php if ($post['status'] === 'published'): ?>

<section class="max-w-4xl mx-auto py-10 px-4">

    <h2 class="text-xl font-bold mb-4">Comments</h2>

    <?php if (isset($_SESSION['user_id'])): ?>

        <form method="POST" class="mb-6">

    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">

    <textarea name="comment"
              class="w-full border p-3 rounded"
              placeholder="Write a comment..."
              required></textarea>

    <button class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">
        Post Comment
    </button>

</form>

    <?php else: ?>

        <p class="text-gray-600 mb-4">
            Please <a href="/BlogSphere/auth/login.php" class="text-blue-600">login</a> to comment.
        </p>

    <?php endif; ?>

    <!-- COMMENT LIST -->
<div class="space-y-4">

    <?php foreach ($comments as $c): ?>

        <div class="bg-white p-4 rounded shadow">

            <div class="flex justify-between items-start">

                <!-- USER + COMMENT -->
                <div>

                    <div class="font-bold text-sm">
                        <?= htmlspecialchars($c['username'] ?? 'User') ?>
                    </div>

                    <div class="text-gray-700 mt-1">
                        <?= htmlspecialchars($c['comment']) ?>
                    </div>

                    <div class="text-xs text-gray-400 mt-1">
                        <?= date('M d, Y', strtotime($c['created_at'])) ?>
                    </div>

                </div>

                <!-- ACTIONS (ONLY OWNER OR ADMIN) -->
                <?php if (
                    isset($_SESSION['user_id']) &&
                    ($_SESSION['user_id'] == $c['user_id'] || ($_SESSION['role'] ?? '') === 'admin')
                ): ?>

                    <div class="flex gap-2 text-sm">

                        <!-- EDIT -->
                        <a href="/BlogSphere/comment_edit.php?id=<?= $c['comment_id'] ?>"
                           class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <!-- DELETE -->
                        <a href="/BlogSphere/comment_delete.php?id=<?= $c['comment_id'] ?>"
                           onclick="return confirm('Delete this comment?')"
                           class="text-red-600 hover:underline">
                            Delete
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

</div>

</section>

<?php endif; ?>

<!-- FOOTER -->
<footer class="text-center py-6 bg-white border-t">
    © <?= date('Y') ?> BlogSphere
</footer>

</body>
</html>