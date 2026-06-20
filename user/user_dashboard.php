<?php

require_once '../config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$totalPostsStmt = $conn->prepare("
    SELECT COUNT(*)
    FROM posts
    WHERE author_id = ?
");
$totalPostsStmt->execute([$userId]);
$totalPosts = $totalPostsStmt->fetchColumn();

$stmt = $conn->prepare("
    SELECT *
    FROM posts
    WHERE author_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$userId]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard | BlogSphere</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white p-6">

        <h1 class="text-2xl font-bold mb-8">
            BlogSphere
        </h1>

        <nav class="space-y-2">

            <a href="user_dashboard.php"
               class="block px-4 py-2 rounded bg-gray-800">
                Dashboard
            </a>

            <a href="../post_create.php"
               class="block px-4 py-2 rounded hover:bg-gray-800">
                Create Post
            </a>

            <a href="../index.php"
               class="block px-4 py-2 rounded hover:bg-gray-800">
                View Site
            </a>

            <a href="../auth/logout.php"
               class="block px-4 py-2 rounded bg-red-600 hover:bg-red-700">
                Logout
            </a>

        </nav>

    </aside>

    <!-- MAIN -->
    <main class="flex-1">

        <!-- TOP BAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between">

            <h2 class="text-xl font-semibold">
                User Dashboard
            </h2>

            <div>
                Welcome,
                <strong>
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </strong>
            </div>

        </div>

        <div class="p-6">

            <!-- STATS -->
            <div class="grid md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500">My Posts</p>
                    <h2 class="text-3xl font-bold">
                        <?= $totalPosts ?>
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500">Role</p>
                    <h2 class="text-2xl font-bold capitalize">
                        <?= htmlspecialchars($_SESSION['role']) ?>
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500">Status</p>
                    <h2 class="text-2xl font-bold text-green-600">
                        Active
                    </h2>
                </div>

            </div>

            <!-- POSTS TABLE -->
<div class="bg-white rounded-xl shadow overflow-hidden">

    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">
            My Posts
        </h3>

        <a href="../post_create.php"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + New Post
        </a>
    </div>

    <table class="w-full text-left">

        <thead class="bg-gray-100">

            <tr>
                <th class="p-3">Title</th>
                <th class="p-3">Status</th>
                <th class="p-3">Date</th>
                <th class="p-3">Actions</th>
            </tr>

        </thead>

        <tbody>

        <?php if(count($posts) > 0): ?>

            <?php foreach($posts as $post): ?>

            <tr class="border-b hover:bg-gray-50">

                <!-- CLICKABLE TITLE -->
                <td class="p-3">

                    <?php if(!empty($post['slug'])): ?>

                        <a href="../post.php?slug=<?= urlencode($post['slug']) ?>"
                           target="_blank"
                           class="text-blue-600 font-medium hover:underline">

                            <?= htmlspecialchars($post['title']) ?>

                        </a>

                    <?php else: ?>

                        <?= htmlspecialchars($post['title']) ?>

                    <?php endif; ?>

                </td>

                <!-- STATUS -->
                <td class="p-3">

                    <?php if ($post['status'] === 'published'): ?>

    <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
        Published
    </span>

<?php elseif ($post['status'] === 'pending'): ?>

    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
        Pending Review
    </span>

<?php elseif ($post['status'] === 'draft'): ?>

    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">
        Draft (Private)
    </span>

<?php elseif ($post['status'] === 'rejected'): ?>

    <span class="bg-red-100 text-red-700 px-2 py-1 rounded">
        Rejected
    </span>

<?php endif; ?>

                </td>

                <!-- DATE -->
                <td class="p-3 text-gray-500">
                    <?= date('M d, Y', strtotime($post['created_at'])) ?>
                </td>

                <!-- ACTIONS -->
                <td class="p-3">

                    <div class="flex gap-2">

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

        <?php else: ?>

            <tr>

                <td colspan="4"
                    class="p-6 text-center text-gray-500">

                    No posts found.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

        </div>

    </main>