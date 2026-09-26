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

<div class="flex min-h-screen relative">

    <!-- MOBILE OVERLAY -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white p-6 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">

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
    <main class="flex-1 w-full">

        <!-- TOP BAR -->
        <div class="bg-white shadow px-4 sm:px-6 py-4 flex justify-between items-center">

            <div class="flex items-center gap-3">

                <button id="sidebarToggle" class="md:hidden p-2 rounded hover:bg-gray-100" aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <h2 class="text-lg sm:text-xl font-semibold">
                    <?= $pageTitle ?? 'Dashboard' ?>
                </h2>

            </div>

            <div class="text-sm sm:text-base text-gray-600 truncate max-w-[140px] sm:max-w-none">
                Welcome, <b><?= htmlspecialchars($_SESSION['username']) ?></b>
            </div>

        </div>

        <!-- CONTENT -->
        <div class="p-4 sm:p-6">

            <?= $content ?>

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