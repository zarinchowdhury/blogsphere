<?php
<<<<<<< HEAD
require_once 'config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid post ID");
}

/* GET POST */
$stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found");
}

/* PERMISSION CHECK */
if ($_SESSION['role'] !== 'admin' && $_SESSION['user_id'] != $post['author_id']) {
    die("Unauthorized access");
}

/* UPDATE POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        UPDATE posts
        SET title = ?, description = ?, content = ?, image_url = ?
        WHERE post_id = ?
    ");

    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['content'],
        $_POST['image_url'],
        $id
    ]);

    $action = $_POST['action'];

    // DEFAULT: keep old status
    $status = $post['status'];

    // ACTION CONTROL
    if ($action === 'save_draft') {
        $status = 'draft';
    }

    if ($action === 'submit_review') {
        $status = 'pending';
    }

    if ($action === 'update') {
        // keep current status unchanged
        $status = $post['status'];
    }

    $stmt = $conn->prepare("
        UPDATE posts
        SET title = ?, description = ?, content = ?, image_url = ?, status = ?
        WHERE post_id = ? AND author_id = ?
    ");

    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['content'],
        $_POST['image_url'],
        $status,
        $id,
        $_SESSION['user_id']
    ]);

    header("Location: " . BASE_URL . "user/user_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Post | BlogSphere</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- HEADER -->
<div class="bg-white shadow">
    <div class="max-w-5xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">
            ✏️ Edit Post
        </h1>

        <a href="<?= BASE_URL ?>user/user_dashboard.php"
           class="text-sm text-blue-600 hover:underline">
            ← Back to Dashboard
        </a>
    </div>
</div>

<!-- MAIN CONTAINER -->
<div class="max-w-5xl mx-auto p-6">

    <form method="POST" class="bg-white rounded-xl shadow-lg p-6 space-y-6">

        <!-- TITLE -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Post Title
            </label>
            <input type="text"
                   name="title"
                   value="<?= htmlspecialchars($post['title']) ?>"
                   class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
        </div>

        <!-- DESCRIPTION -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Short Description
            </label>
            <textarea name="description"
                      rows="3"
                      class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"><?= htmlspecialchars($post['description']) ?></textarea>
        </div>

        <!-- CONTENT -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Content
            </label>
            <textarea name="content"
                      rows="10"
                      class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"><?= htmlspecialchars($post['content']) ?></textarea>
        </div>

        <!-- IMAGE URL -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Image URL
            </label>
            <input type="text"
                   name="image_url"
                   value="<?= htmlspecialchars($post['image_url']) ?>"
                   class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <?php if (!empty($post['image_url'])): ?>
                <img src="<?= htmlspecialchars($post['image_url']) ?>"
                     class="mt-3 w-full h-60 object-cover rounded-lg shadow">
            <?php endif; ?>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex flex-wrap gap-3 pt-6 border-t">

    <!-- SAVE DRAFT -->
    <button type="submit" name="action" value="save_draft"
        class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
        💾 Save Draft
    </button>

    <!-- UPDATE ONLY -->
    <button type="submit" name="action" value="update"
        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        ✏️ Update
    </button>

    <!-- SUBMIT FOR REVIEW -->
    <button type="submit" name="action" value="submit_review"
        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        📤 Submit for Review
    </button>

</div>
    </form>
=======

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

```
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

</form>
```
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9

</div>

</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9
