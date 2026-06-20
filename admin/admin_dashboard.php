<?php
require_once '../config/app.php';
require_once '../includes/admin_guard.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* =========================
   STATS
========================= */
$totalUsers = $conn->query("
    SELECT COUNT(*)
    FROM users
")->fetchColumn();

$totalPosts = $conn->query("
    SELECT COUNT(*)
    FROM posts
")->fetchColumn();

$totalCategories = $conn->query("
    SELECT COUNT(*)
    FROM categories
")->fetchColumn();

/* =========================
   PENDING POSTS
========================= */
$pendingPosts = $conn->query("
    SELECT p.post_id, p.title, p.created_at, u.username
    FROM posts p
    JOIN users u ON p.author_id = u.user_id
    WHERE p.status = 'pending'
    ORDER BY p.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   LATEST POSTS
========================= */
$stmt = $conn->query("
    SELECT
        p.post_id,
        p.title,
        p.status,
        p.created_at,
        c.name AS category_name
    FROM posts p
    LEFT JOIN categories c
        ON p.category_id = c.category_id
    ORDER BY p.created_at DESC
    LIMIT 10
");

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | BlogSphere</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-white p-6">

        <h1 class="text-2xl font-bold mb-8">
            BlogSphere
        </h1>

        <nav class="space-y-2">

            <a href="admin_dashboard.php"
               class="block px-4 py-3 rounded-lg bg-slate-800">
                Dashboard
            </a>

            <a href="posts.php"
               class="block px-4 py-3 rounded-lg hover:bg-slate-800">
                Posts
            </a>

            <a href="post_create.php"
               class="block px-4 py-3 rounded-lg hover:bg-slate-800">
                Create Post
            </a>

            <a href="users.php"
               class="block px-4 py-3 rounded-lg hover:bg-slate-800">
                Users
            </a>

            <a href="../index.php"
               target="_blank"
               class="block px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700">
                View Site
            </a>

            <a href="../auth/logout.php"
               class="block px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700">
                Logout
            </a>

        </nav>

    </aside>

    <!-- MAIN -->
    <main class="flex-1">

        <!-- TOP NAVBAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <h2 class="text-xl font-bold">
                Admin Dashboard
            </h2>

            <div class="text-gray-600">
                Welcome,
                <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
            </div>

        </div>

        <div class="p-6 space-y-6">

            <!-- =========================
                 STATS FIRST
            ========================== -->

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-xl shadow">

                    <p class="text-gray-500">
                        Users
                    </p>

                    <h2 class="text-4xl font-bold mt-2">
                        <?= $totalUsers ?>
                    </h2>

                </div>

                <div class="bg-white p-6 rounded-xl shadow">

                    <p class="text-gray-500">
                        Posts
                    </p>

                    <h2 class="text-4xl font-bold mt-2">
                        <?= $totalPosts ?>
                    </h2>

                </div>

                <div class="bg-white p-6 rounded-xl shadow">

                    <p class="text-gray-500">
                        Categories
                    </p>

                    <h2 class="text-4xl font-bold mt-2">
                        <?= $totalCategories ?>
                    </h2>

                </div>

            </div>

            <!-- =========================
                 PENDING POSTS
            ========================== -->

            <?php if (!empty($pendingPosts)): ?>

            <div class="bg-white rounded-xl shadow overflow-hidden">

                <div class="p-4 border-b">

                    <h3 class="font-semibold text-lg text-orange-600">
                        Pending Approval Posts
                    </h3>

                </div>

                <table class="w-full text-left">

                    <thead class="bg-orange-50">

                        <tr>
                            <th class="p-3">Title</th>
                            <th class="p-3">Author</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($pendingPosts as $p): ?>

                        <tr class="border-b hover:bg-gray-50">

    <td class="p-3">

        <a href="view_post.php?id=<?= $p['post_id'] ?>"
           class="font-semibold text-blue-600 hover:underline">

            <?= htmlspecialchars($p['title']) ?>

        </a>

    </td>

    <td class="p-3">
        <?= htmlspecialchars($p['username']) ?>
    </td>

    <td class="p-3 text-gray-500">

        📅 <?= date('M d, Y', strtotime($p['created_at'])) ?>

    </td>

    <td class="p-3">

        <div class="flex flex-wrap gap-2">

            <a href="approve_post.php?id=<?= $p['post_id'] ?>"
               class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                Approve
            </a>

            <a href="reject_post.php?id=<?= $p['post_id'] ?>"
               onclick="return confirm('Reject this post?')"
               class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                Reject
            </a>

           <a href="/BlogSphere/post_edit.php?id=<?= $post['post_id'] ?>"
   class="bg-yellow-500 text-white px-3 py-1 rounded">
    Edit
</a>

            <a href="/BlogSphere/post_delete.php?id=<?= $post['post_id'] ?>"
   onclick="return confirm('Delete this post?')"
   class="bg-red-600 text-white px-3 py-1 rounded">
    Delete
</a>

        </div>

    </td>

</tr>
                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <?php endif; ?>

            <!-- =========================
                 LATEST POSTS
            ========================== -->

            <div class="bg-white rounded-xl shadow overflow-hidden">

                <div class="p-4 border-b flex justify-between items-center">

                    <h3 class="font-semibold text-lg">
                        Latest Posts
                    </h3>

                    <a href="posts.php"
                       class="text-blue-600 hover:underline">
                        View All
                    </a>

                </div>

                <table class="w-full text-left">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="p-3">Title</th>
                            <th class="p-3">Category</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Date</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($posts as $post): ?>

                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-3 font-medium">
                                <?= htmlspecialchars($post['title']) ?>
                            </td>

                            <td class="p-3">
                                <?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?>
                            </td>

                            <td class="p-3">

                                <?php if ($post['status'] === 'published'): ?>

    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
        Published
    </span>

<?php elseif ($post['status'] === 'pending'): ?>

    <span class="px-2 py-1 rounded text-xs bg-orange-100 text-orange-700">
        Pending Approval
    </span>

<?php elseif ($post['status'] === 'rejected'): ?>

    <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
        Rejected
    </span>

<?php else: ?>

    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">
        Draft
    </span>

<?php endif; ?>

                            </td>

                            <td class="p-3 text-sm text-gray-500">
                                <?= date('M d, Y', strtotime($post['created_at'])) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>
</html>