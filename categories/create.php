<?php

require_once '../config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $slug = slugify($name);

    if (!$name) {
        $error = "Category name required";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO categories (name, slug)
            VALUES (?, ?)
        ");

        $stmt->execute([$name, $slug]);

        header("Location: list.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Create Category</h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-600 p-2 mb-3">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="name"
            class="w-full p-2 border mb-3"
            placeholder="Category name">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Save
        </button>

    </form>

</div>

</body>
</html>