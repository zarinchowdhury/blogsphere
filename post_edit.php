<?php
require_once 'config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /BlogSphere/auth/login.php");
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

    header("Location: /BlogSphere/user/user_dashboard.php");
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

        <a href="/BlogSphere/user/user_dashboard.php"
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

</div>

</body>
</html>