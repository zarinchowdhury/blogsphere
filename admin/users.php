<?php
require_once '../config/app.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* PROMOTE TO AUTHOR */
if (isset($_GET['make_author'])) {

    $id = (int)$_GET['make_author'];

    $stmt = $conn->prepare("
        UPDATE users
        SET role = 'author'
        WHERE user_id = ?
    ");

    $stmt->execute([$id]);

    header("Location: users.php");
    exit;
}

/* REMOVE AUTHOR */
if (isset($_GET['remove_author'])) {

    $id = (int)$_GET['remove_author'];

    $stmt = $conn->prepare("
        UPDATE users
        SET role = 'user'
        WHERE user_id = ?
    ");

    $stmt->execute([$id]);

    header("Location: users.php");
    exit;
}

/* FETCH USERS */
$users = $conn->query("
    SELECT user_id, username, email, role
    FROM users
    ORDER BY user_id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | BlogSphere</title>

<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen relative">

    <!-- MOBILE OVERLAY -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white p-6 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">

        <h1 class="text-2xl font-bold mb-8">
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

            <a href="users.php"
               class="block px-3 py-2 rounded bg-gray-800">
               Users
            </a>

            <a href="../index.php"
               target="_blank"
               class="block px-3 py-2 rounded bg-blue-600 hover:bg-blue-700">
               View Site
            </a>

            <a href="../auth/logout.php"
               class="block px-3 py-2 rounded bg-red-600 hover:bg-red-700">
               Logout
            </a>

        </nav>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 w-full">

        <div class="bg-white shadow px-4 sm:px-6 py-4 flex items-center gap-3">

            <button id="sidebarToggle" class="md:hidden p-2 rounded hover:bg-gray-100" aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <h2 class="text-lg sm:text-xl font-semibold">
                User Management
            </h2>

        </div>

        <div class="p-4 sm:p-6">

            <div class="bg-white rounded-xl shadow overflow-hidden">

                <div class="overflow-x-auto">

                <table class="w-full min-w-[640px]">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="p-4 text-left">ID</th>
                            <th class="p-4 text-left">Username</th>
                            <th class="p-4 text-left">Email</th>
                            <th class="p-4 text-left">Role</th>
                            <th class="p-4 text-left">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach($users as $user): ?>

                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-4">
                                <?= $user['user_id'] ?>
                            </td>

                            <td class="p-4">
                                <?= htmlspecialchars($user['username']) ?>
                            </td>

                            <td class="p-4">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>

                            <td class="p-4">

                                <?php if($user['role'] === 'admin'): ?>

                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">
                                        Admin
                                    </span>

                                <?php elseif($user['role'] === 'author'): ?>

                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                        Author
                                    </span>

                                <?php else: ?>

                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                        User
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="p-4">

                                <?php if($user['role'] === 'user'): ?>

                                    <a href="?make_author=<?= $user['user_id'] ?>"
                                       class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                                        Make Author
                                    </a>

                                <?php elseif($user['role'] === 'author'): ?>

                                    <a href="?remove_author=<?= $user['user_id'] ?>"
                                       class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Remove Author
                                    </a>

                                <?php else: ?>

                                    <span class="text-gray-400 text-sm">
                                        Protected
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

                </div>

            </div>

        </div>

    </main>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const toggleBtn = document.getElementById("sidebarToggle");

    function openSidebar() {
        sidebar?.classList.remove("-translate-x-full");
        overlay?.classList.remove("hidden");
    }
    function closeSidebar() {
        sidebar?.classList.add("-translate-x-full");
        overlay?.classList.add("hidden");
    }

    toggleBtn?.addEventListener("click", openSidebar);
    overlay?.addEventListener("click", closeSidebar);
});
</script>

</body>
</html>