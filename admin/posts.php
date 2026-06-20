<?php
require_once '../config/app.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $conn->query("
    SELECT p.*, c.name AS category_name
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.created_at DESC
");

$posts = $stmt->fetchAll();

ob_start();
?>

<div class="flex justify-between mb-6">

    <h2 class="text-2xl font-bold">All Posts</h2>

    <a href="post_create.php"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        + New Post
    </a>

</div>

<div class="bg-white shadow rounded-xl overflow-hidden">

<table class="w-full">

    <thead class="bg-gray-100 text-left">
        <tr>
            <th class="p-3">Title</th>
            <th class="p-3">Category</th>
            <th class="p-3">Status</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($posts as $post): ?>

        <tr class="border-b hover:bg-gray-50">

            <td class="p-3 font-medium">
                <?= htmlspecialchars($post['title']) ?>
            </td>

            <td class="p-3 text-gray-600">
                <?= htmlspecialchars($post['category_name']) ?>
            </td>

            <td class="p-3">
                <span class="px-2 py-1 text-xs rounded
                    <?= $post['status'] === 'published'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-yellow-100 text-yellow-700' ?>">
                    <?= $post['status'] ?>
                </span>
            </td>

            <td class="p-3 space-x-3">

               <a href="/BlogSphere/post_edit.php?id=<?= $post['post_id'] ?>"
   class="bg-yellow-500 text-white px-3 py-1 rounded">
    Edit
</a>
<a href="/BlogSphere/post_delete.php?id=<?= $post['post_id'] ?>"
   onclick="return confirm('Delete this post?')"
   class="bg-red-600 text-white px-3 py-1 rounded">
    Delete
</a>
            </td>

        </tr>

        <?php endforeach; ?>

    </tbody>

</table>

</div>

<?php
$content = ob_get_clean();
$pageTitle = "Posts";
include "layout.php";
?>