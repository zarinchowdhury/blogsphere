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

$cats = $conn->query("SELECT * FROM categories ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-4xl mx-auto mt-10">

    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Categories</h1>
        <a href="create.php" class="bg-blue-600 text-white px-3 py-1 rounded">
            + New
        </a>
    </div>

    <div class="bg-white shadow rounded">

        <?php foreach ($cats as $cat): ?>
            <div class="p-4 border-b flex justify-between">
                <span><?= htmlspecialchars($cat['name']) ?></span>
                <span class="text-gray-400"><?= $cat['slug'] ?></span>
            </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>