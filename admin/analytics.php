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

/* ---------------- DATA ---------------- */

// totals
$totalPosts = $conn->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalCategories = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();

/* ---------------- CATEGORY DATA ---------------- */
$catStmt = $conn->query("
    SELECT c.name, COUNT(p.post_id) AS total
    FROM categories c
    LEFT JOIN posts p ON c.category_id = p.category_id
    GROUP BY c.category_id
");

$categoryData = $catStmt->fetchAll(PDO::FETCH_ASSOC);

$categoryChart = [];
foreach ($categoryData as $c) {
    $categoryChart[] = [
        "category" => $c['name'],
        "count" => (int)$c['total']
    ];
}

/* ---------------- POST TREND ---------------- */
$trendStmt = $conn->query("
    SELECT DATE(created_at) as date, COUNT(*) as total
    FROM posts
    GROUP BY DATE(created_at)
    ORDER BY date DESC
    LIMIT 7
");

$trendData = array_reverse($trendStmt->fetchAll(PDO::FETCH_ASSOC));

$trendChart = [];
foreach ($trendData as $t) {
    $trendChart[] = [
        "date" => $t['date'],
        "posts" => (int)$t['total']
    ];
}

/* ---------------- TOP POSTS ---------------- */
$topPosts = $conn->query("
    SELECT title, views
    FROM posts
    ORDER BY views DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$topChart = [];
foreach ($topPosts as $p) {
    $topChart[] = [
        "title" => $p['title'],
        "views" => (int)$p['views']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-7xl mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6">📊 Blog Analytics Dashboard</h1>

    <!-- STATS -->
    <div class="grid md:grid-cols-3 gap-6 mb-10">

        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-gray-500">Total Posts</h2>
            <p class="text-3xl font-bold text-blue-600"><?= $totalPosts ?></p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-gray-500">Users</h2>
            <p class="text-3xl font-bold text-green-600"><?= $totalUsers ?></p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-gray-500">Categories</h2>
            <p class="text-3xl font-bold text-purple-600"><?= $totalCategories ?></p>
        </div>

    </div>

    <!-- CHARTS -->
    <div class="grid md:grid-cols-2 gap-6">

        <!-- CATEGORY CHART -->
        <div class="bg-white p-6 rounded shadow">

            <h2 class="font-bold mb-4">Posts by Category</h2>

            <pre class="text-xs bg-gray-100 p-2 rounded overflow-auto">
<?= json_encode($categoryChart, JSON_PRETTY_PRINT) ?>
            </pre>

        </div>

        <!-- TREND CHART -->
        <div class="bg-white p-6 rounded shadow">

            <h2 class="font-bold mb-4">Posting Activity (Last Days)</h2>

            <pre class="text-xs bg-gray-100 p-2 rounded overflow-auto">
<?= json_encode($trendChart, JSON_PRETTY_PRINT) ?>
            </pre>

        </div>

    </div>

    <!-- TOP POSTS -->
    <div class="mt-10 bg-white p-6 rounded shadow">

        <h2 class="font-bold mb-4">🔥 Top Posts by Views</h2>

        <pre class="text-xs bg-gray-100 p-2 rounded overflow-auto">
<?= json_encode($topChart, JSON_PRETTY_PRINT) ?>
        </pre>

    </div>

</div>

</body>
</html>