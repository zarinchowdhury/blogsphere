<?php

require_once '../config/app.php';
require_once '../config/database.php';
require_once '../auth.php';

requireLogin();

$database = new Database();
$conn = $database->connect();

$error = "";

/*
|--------------------------------------------------------------------------
| Load Categories
|--------------------------------------------------------------------------
*/
$categories = $conn->query("
    SELECT *
    FROM categories
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Create Post
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category_id = $_POST['category_id'] ?? null;

    if (empty($title) || empty($content) || empty($category_id)) {

        $error = "Title, category and content are required.";

    } else {

        $slug = slugify($title);

        /*
        |--------------------------------------------------------------------------
        | Image Upload
        |--------------------------------------------------------------------------
        */
        $image_url = "default-post.jpg";

        if (
            isset($_FILES['thumbnail']) &&
            $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK
        ) {

            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            $ext = strtolower(
                pathinfo(
                    $_FILES['thumbnail']['name'],
                    PATHINFO_EXTENSION
                )
            );

            if (in_array($ext, $allowed)) {

                $image_url =
                    time() . "_" .
                    basename($_FILES['thumbnail']['name']);

                move_uploaded_file(
                    $_FILES['thumbnail']['tmp_name'],
                    "../assets/images/" . $image_url
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save Post
        |--------------------------------------------------------------------------
        */
        $stmt = $conn->prepare("
            INSERT INTO posts (
                author_id,
                category_id,
                title,
                description,
                slug,
                content,
                image_url,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, 'published')
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            $category_id,
            $title,
            $description,
            $slug,
            $content,
            $image_url
        ]);

        header("Location: ../index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 min-h-screen">

<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-lg">

    <h2 class="text-2xl font-bold mb-6 text-blue-700">
        Create New Post
    </h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <!-- Title -->
        <input
            type="text"
            name="title"
            placeholder="Post Title"
            class="w-full p-3 border rounded mb-4"
        >

        <!-- Description -->
        <textarea
            name="description"
            rows="3"
            placeholder="Short description..."
            class="w-full p-3 border rounded mb-4"
        ></textarea>

        <!-- Category -->
        <select name="category_id" class="w-full p-3 border rounded mb-4">
            <option value="">Select Category</option>

            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <!-- Content -->
        <textarea
            name="content"
            rows="10"
            placeholder="Write your post..."
            class="w-full p-3 border rounded mb-4"
        ></textarea>

        <!-- Image -->
        <input
            type="file"
            name="thumbnail"
            class="w-full p-3 border rounded mb-4"
        >

        <!-- Submit -->
        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded"
        >
            Publish Post
        </button>

    </form>

</div>

</body>
</html>