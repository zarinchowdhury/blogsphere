<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $title ?? 'Admin Panel' ?> | BlogSphere</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white p-6">

        <h1 class="text-2xl font-bold mb-10">
            BlogSphere
        </h1>

        <nav class="space-y-2">

    <a href="admin_dashboard.php"
       class="block px-3 py-2 rounded hover:bg-gray-800">
        Dashboard
    </a>

    <a href="posts.php"
       class="block px-3 py-2 rounded hover:bg-gray-800">
        Posts
    </a>

    <a href="post_create.php"
       class="block px-3 py-2 rounded hover:bg-gray-800">
        Create Post
    </a>
    
    <a href="../index.php"
   target="_blank"
   class="block px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 font-medium">
    View Site
</a>
    <a href="../auth/logout.php"
       class="block px-3 py-2 rounded bg-red-600 hover:bg-red-700">
        Logout
    </a>

</nav>

    </aside>

    <!-- MAIN -->
    <main class="flex-1">

        <!-- TOP BAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <h2 class="text-xl font-semibold">
                <?= $pageTitle ?? 'Dashboard' ?>
            </h2>

            <div class="text-gray-600">
                Welcome, <b><?= htmlspecialchars($_SESSION['username']) ?></b>
            </div>

        </div>

        <!-- CONTENT -->
        <div class="p-6">

            <?= $content ?>

        </div>

    </main>

</div>

</body>
</html>