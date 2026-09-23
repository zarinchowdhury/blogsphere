<?php
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

</div>

</body>
</html>