<?php

require_once '../config/app.php';
require_once '../includes/admin_guard.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>
try {

    // Use single connection block for better structure
    $postCount = (int) $conn->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $userCount = (int) $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $catCount  = (int) $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Reusable stats array (clean + scalable)
$stats = [
    [
        "title" => "Posts",
        "count" => $postCount,
        "color" => "text-blue-600"
    ],
    [
        "title" => "Users",
        "count" => $userCount,
        "color" => "text-green-600"
    ],
    [
        "title" => "Categories",
        "count" => $catCount,
        "color" => "text-purple-600"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-6 py-5">
            <h1 class="text-2xl font-bold text-gray-800">
                Admin Dashboard
            </h1>
            <div class="mb-6">
    <a href="/BlogSphere/admin/analytics.php"
        class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
        📊 Analytics Dashboard
    </a>
</div>
            <p class="text-sm text-gray-500">
                Manage your BlogSphere platform
            </p>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-6xl mx-auto px-6 py-10">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <?php foreach ($stats as $stat): ?>
                <div class="bg-white rounded-lg shadow hover:shadow-md transition p-6">

                    <h2 class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        <?= htmlspecialchars($stat["title"]) ?>
                    </h2>

                    <p class="text-3xl font-bold mt-2 <?= $stat["color"] ?>">
                        <?= htmlspecialchars($stat["count"]) ?>
                    </p>

                </div>
            <?php endforeach; ?>

        </div>

    </main>

</body>
</html>