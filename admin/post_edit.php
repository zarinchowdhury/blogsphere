<?php

require_once '../config/app.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* ADMIN CHECK */
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Post ID
|--------------------------------------------------------------------------
*/

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: posts.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Fetch Post
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM posts
    WHERE post_id = ?
");

$stmt->execute([$id]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

/*
|--------------------------------------------------------------------------
| Fetch Categories
|--------------------------------------------------------------------------
*/

$categories = $conn->query("
    SELECT category_id, name
    FROM categories
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$error = '';

/*
|--------------------------------------------------------------------------
| Update Post
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $image_url = trim($_POST['image_url']);
    $status = $_POST['status'];

    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');

    if (empty($title) || empty($content)) {

        $error = "Title and content are required.";

    } else {

        $stmt = $conn->prepare("
            UPDATE posts
            SET
                title = ?,
                description = ?,
                content = ?,
                category_id = ?,
                image_url = ?,
                slug = ?,
                status = ?
            WHERE post_id = ?
        ");

        $stmt->execute([
            $title,
            $description,
            $content,
            $category_id,
            $image_url,
            $slug,
            $status,
            $id
        ]);

        header("Location: posts.php");
        exit;
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Post | BlogSphere Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-4xl mx-auto py-10 px-4">

<div class="flex flex-wrap gap-3 justify-between items-center mb-8">

<div class="flex justify-between items-center mb-8">

    <h1 class="text-3xl font-bold">
        Edit Post
    </h1>

    <div class="space-x-3">

        <a href="admin_dashboard.php"
           class="bg-gray-700 text-white px-4 py-2 rounded-lg">
            Dashboard
        </a>

        <a href="posts.php"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Manage Posts
        </a>

    </div>

</div>

<?php if (!empty($error)): ?>

    <div class="bg-red-100 text-red-700 p-3 rounded mb-5">
        <?= htmlspecialchars($error) ?>
    </div>

<?php endif; ?>

<form method="POST"
      class="bg-white p-8 rounded-xl shadow-lg space-y-5">

    <div>

        <label class="block mb-2 font-semibold">
            Title
        </label>

        <input
            type="text"
            name="title"
            value="<?= htmlspecialchars($post['title']) ?>"
            required
            class="w-full border rounded-lg p-3"
        >

    </div>

    <div>

        <label class="block mb-2 font-semibold">
            Short Description
        </label>

        <textarea
            name="description"
            rows="3"
            class="w-full border rounded-lg p-3"
        ><?= htmlspecialchars($post['description']) ?></textarea>

    </div>

    <div>

        <label class="block mb-2 font-semibold">
            Content
        </label>

        <textarea
            name="content"
            rows="12"
            required
            class="w-full border rounded-lg p-3"
        ><?= htmlspecialchars($post['content']) ?></textarea>

    </div>

    <div>

        <label class="block mb-2 font-semibold">
            Featured Image URL
        </label>

        <input
            type="text"
            name="image_url"
            value="<?= htmlspecialchars($post['image_url']) ?>"
            class="w-full border rounded-lg p-3"
        >

    </div>

    <div>

        <label class="block mb-2 font-semibold">
            Category
        </label>

        <select
            name="category_id"
            class="w-full border rounded-lg p-3"
        >

            <?php foreach ($categories as $category): ?>

                <option
                    value="<?= $category['category_id'] ?>"
                    <?= $category['category_id'] == $post['category_id'] ? 'selected' : '' ?>
                >

                    <?= htmlspecialchars($category['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div>

        <label class="block mb-2 font-semibold">
            Status
        </label>

        <select
            name="status"
            class="w-full border rounded-lg p-3"
        >

            <option
                value="published"
                <?= $post['status'] === 'published' ? 'selected' : '' ?>
            >
                Published
            </option>

            <option
                value="draft"
                <?= $post['status'] === 'draft' ? 'selected' : '' ?>
            >
                Draft
            </option>

        </select>

    </div>

    <button
        type="submit"
        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold"
    >
        Update Post
    </button>


````php
</form>

</div>

</body>
</html>
