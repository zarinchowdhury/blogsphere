<?php
<<<<<<< HEAD
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$categories = $conn->query("SELECT category_id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $image_url = $_POST['image_url'] ?? null;

    $slug = strtolower(trim(str_replace(' ', '-', $title)));

    // default workflow
   $status = $_POST['status'] ?? 'draft';

/* IMPORTANT LOGIC */
if ($status === 'draft') {
    $approval_status = 'draft';   // stays private
} else {
    $approval_status = 'pending'; // goes to admin
}

   $stmt = $conn->prepare("
    INSERT INTO posts
    (author_id, category_id, title, description, content, slug, image_url, status, approval_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $_SESSION['user_id'],
    $_POST['category_id'],
    $_POST['title'],
    $_POST['description'],
    $_POST['content'],
    strtolower(str_replace(' ', '-', $_POST['title'])),
    $_POST['image_url'],
    $status,
    $approval_status
]);

    header("Location: user/user_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-3xl mx-auto py-10">

<h1 class="text-2xl font-bold mb-6">Create Post</h1>

<form method="POST" class="bg-white p-6 rounded shadow space-y-4">

    <input name="title" placeholder="Title" class="w-full border p-2" required>

    <textarea name="description" placeholder="Short Description" class="w-full border p-2"></textarea>

    <textarea name="content" placeholder="Content" class="w-full border p-2 h-40" required></textarea>

    <input name="image_url" placeholder="Image URL" class="w-full border p-2">

    <select name="category_id" class="w-full border p-2" required>
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c['category_id'] ?>">
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <select name="status" class="w-full border p-2">
            <option value="published">Publish Now</option>
            <option value="draft"> Draft</option>
        </select>
    <?php else: ?>
        <input type="hidden" name="status" value="pending">
        <p class="text-yellow-600">Your post will be sent for admin approval.</p>
    <?php endif; ?>

    <div class="flex gap-3 mt-6">

    <!-- SAVE DRAFT -->
    <button type="submit" name="status" value="draft"
        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        Save Draft
    </button>

    <!-- SUBMIT FOR REVIEW -->
    <button type="submit" name="status" value="pending"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Submit for Review
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
| Fetch Categories
|--------------------------------------------------------------------------
*/

$stmt = $conn->query("
    SELECT category_id, name
    FROM categories
    ORDER BY name ASC
");

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $image_url = trim($_POST['image_url']);
    $action = $_POST['action'] ?? 'draft';

$status = ($action === 'publish') ? 'published' : 'draft';

    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');

    if (
        empty($title) ||
        empty($content) ||
        empty($category_id)
    ) {
        $error = "Please fill all required fields.";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO posts
            (
                author_id,
                category_id,
                title,
                description,
                content,
                slug,
                image_url,
                status
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            $category_id,
            $title,
            $description,
            $content,
            $slug,
            $image_url,
            $status
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

<title>Create Post | BlogSphere Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-4xl mx-auto py-10 px-4">

```
<div class="flex justify-between items-center mb-8">

    <h1 class="text-3xl font-bold">
        Create New Post
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
        ></textarea>

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
        ></textarea>

    </div>

    <div>

        <label class="block mb-2 font-semibold">
            Featured Image URL
        </label>

        <input
            type="text"
            name="image_url"
            class="w-full border rounded-lg p-3"
            placeholder="https://example.com/image.jpg"
        >

    </div>

    <div>

        <label class="block mb-2 font-semibold">
            Category
        </label>

        <select
            name="category_id"
            required
            class="w-full border rounded-lg p-3"
        >

            <?php foreach ($categories as $category): ?>

                <option value="<?= $category['category_id'] ?>">

                    <?= htmlspecialchars($category['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div>

        <!-- STATUS OPTIONS -->
<label class="block font-semibold mb-2">Status</label>

<select name="status" class="w-full border p-2 rounded">

    <option value="draft">
        💾 Save as Draft (Private)
    </option>

    <option value="pending">
        📤 Submit for Admin Review
    </option>

</select>
    </div>

    <button
        <div class="flex gap-3">

    <button type="submit" name="action" value="publish"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Publish
    </button>

    <button type="submit" name="action" value="draft"
        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        Save Draft
    </button>

</div>
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
