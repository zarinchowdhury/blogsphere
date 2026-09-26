<?php
require_once 'config/app.php';

$loggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? 'guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BlogSphere - Home</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR -->
<header class="bg-white shadow-sm sticky top-0 z-50">

<div class="max-w-7xl mx-auto flex justify-between items-center px-4 sm:px-6 py-4">

    <a href="<?= BASE_URL ?>index.php"
        class="text-2xl font-bold text-blue-600">
        BlogSphere
    </a>

    <!-- DESKTOP NAV (unchanged) -->
    <nav class="hidden md:flex items-center gap-6 text-sm">

        <a href="<?= BASE_URL ?>index.php" class="hover:text-blue-600">
            Home
        </a>

        <a href="<?= BASE_URL ?>about.php" class="hover:text-blue-600">
            About
        </a>

        <!-- CATEGORY DROPDOWN (FIXED - PURE CSS) -->
        <div class="relative category-menu">

    <button data-category-btn
        class="text-gray-700 hover:text-blue-600">
        Categories ▼
    </button>

    <div id="categoryDropdown"
        class="hidden absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border z-50 overflow-hidden">

        <a href="<?= BASE_URL ?>category.php?slug=business"
           class="block px-4 py-3 hover:bg-gray-100">
            Business
        </a>

        <a href="<?= BASE_URL ?>category.php?slug=travel"
           class="block px-4 py-3 hover:bg-gray-100">
            Travel
        </a>

        <a href="<?= BASE_URL ?>category.php?slug=lifestyle"
           class="block px-4 py-3 hover:bg-gray-100">
            Lifestyle
        </a>

        <a href="<?= BASE_URL ?>category.php?slug=technology"
           class="block px-4 py-3 hover:bg-gray-100">
            Technology
        </a>

    </div>

</div>

        <!-- AUTH BUTTONS -->
        <?php if (!empty($_SESSION['user_id'])): ?>

<div class="relative user-menu">

    <!-- USER BUTTON -->
<button data-user-btn class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
    <?= htmlspecialchars($username) ?> ▼
</button>


    <!-- DROPDOWN -->
    <div id="userMenu"
     class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border overflow-hidden z-50">

               <!-- USER DASHBOARD (ONLY FOR NON-ADMINS) -->
        <?php if (($_SESSION['role'] ?? '') !== 'admin'): ?>
        <a href="<?= BASE_URL ?>user/user_dashboard.php"
class="block px-4 py-2 hover:bg-gray-100">
    User Dashboard
</a>
        <?php endif; ?>


        <!-- CREATE POST -->
        <a href="<?= BASE_URL ?>post_create.php"
            class="block px-4 py-2 hover:bg-gray-100">
            Create Post
        </a>

        <!-- ADMIN DASHBOARD (ONLY ADMIN) -->
        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="<?= BASE_URL ?>admin/admin_dashboard.php"
                class="block px-4 py-2 text-blue-600 font-semibold hover:bg-gray-100">
                Admin Dashboard
            </a>
        <?php endif; ?>

        <hr>

        <!-- LOGOUT -->
        <a href="<?= BASE_URL ?>auth/logout.php"
            class="block px-4 py-2 text-red-600 hover:bg-red-50">
            Logout
        </a>

    </div>

</div>

<?php else: ?>

<!-- LOGIN / REGISTER -->
<a href="<?= BASE_URL ?>auth/login.php"
    class="px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition">
    Login
</a>

<a href="<?= BASE_URL ?>auth/register.php"
    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
    Register
</a>

<?php endif; ?>

    </nav>

    <!-- MOBILE HAMBURGER -->
    <button id="mobileMenuBtn" class="md:hidden p-2 rounded hover:bg-gray-100" aria-label="Open menu">
        <svg id="iconOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

</div>

<!-- MOBILE MENU PANEL -->
<div id="mobileMenu" class="hidden md:hidden border-t bg-white px-4 py-3 space-y-1">

    <a href="<?= BASE_URL ?>index.php" class="block py-2 text-gray-700 hover:text-blue-600">Home</a>
    <a href="<?= BASE_URL ?>about.php" class="block py-2 text-gray-700 hover:text-blue-600">About</a>

    <p class="pt-2 text-xs uppercase tracking-wide text-gray-400">Categories</p>
    <a href="<?= BASE_URL ?>category.php?slug=business" class="block py-2 pl-2 text-gray-600 hover:text-blue-600">Business</a>
    <a href="<?= BASE_URL ?>category.php?slug=travel" class="block py-2 pl-2 text-gray-600 hover:text-blue-600">Travel</a>
    <a href="<?= BASE_URL ?>category.php?slug=lifestyle" class="block py-2 pl-2 text-gray-600 hover:text-blue-600">Lifestyle</a>
    <a href="<?= BASE_URL ?>category.php?slug=technology" class="block py-2 pl-2 text-gray-600 hover:text-blue-600">Technology</a>

    <hr class="my-2">

    <?php if (!empty($_SESSION['user_id'])): ?>

               <?php if (($_SESSION['role'] ?? '') !== 'admin'): ?>
        <a href="<?= BASE_URL ?>user/user_dashboard.php" class="block py-2 text-gray-700 hover:text-blue-600">User Dashboard</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>post_create.php" class="block py-2 text-gray-700 hover:text-blue-600">Create Post</a>

        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="<?= BASE_URL ?>admin/admin_dashboard.php" class="block py-2 text-blue-600 font-semibold">Admin Dashboard</a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>auth/logout.php" class="block py-2 text-red-600">Logout</a>

    <?php else: ?>

        <a href="<?= BASE_URL ?>auth/login.php" class="block py-2 text-blue-600 font-medium">Login</a>
        <a href="<?= BASE_URL ?>auth/register.php" class="block py-2 bg-blue-600 text-white text-center rounded mt-1">Register</a>

    <?php endif; ?>

</div>

</header>

<!-- HERO -->
<section class="relative">

<div class="h-[420px] sm:h-[480px] md:h-[520px] bg-cover bg-center flex items-center justify-center"
    style="background-image:url('https://images.unsplash.com/photo-1455390582262-044cdead277a');">

    <div class="bg-black/60 text-center text-white p-6 sm:p-10 rounded-2xl max-w-2xl mx-4">

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-4">
            Build & Share Your Ideas
        </h1>

        <p class="text-base sm:text-lg text-gray-200 mb-6">
            A modern blogging platform for creators, thinkers, and entrepreneurs.
        </p>

        <?php if (!$loggedIn): ?>

            <a href="<?= BASE_URL ?>auth/register.php"
                class="inline-block bg-blue-600 px-6 py-3 rounded-lg hover:bg-blue-700">
                Get Started
            </a>

        <?php else: ?>

            <a href="<?= BASE_URL ?>blogs.php"
    class="bg-blue-600 px-6 py-3 rounded hover:bg-blue-700">
    Explore Blogs
</a>
        <?php endif; ?>

    </div>
</div>
</section>

<!-- CATEGORIES -->
<section class="max-w-7xl mx-auto py-16 px-4 sm:px-6">

<h2 class="text-2xl sm:text-3xl font-bold text-center mb-10">
    Explore Categories
</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">

<?php
$categories = [
    ["business", "Business", "https://images.unsplash.com/photo-1521791136064-7986c2920216"],
    ["travel", "Travel", "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee"],
    ["lifestyle", "Lifestyle", "https://images.unsplash.com/photo-1524758631624-e2822e304c36"],
    ["technology", "Technology", "https://images.unsplash.com/photo-1518770660439-4636190af475"]
];
?>

<?php foreach ($categories as $c): ?>
<div class="bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden">

    <img src="<?= $c[2] ?>" class="h-32 sm:h-44 w-full object-cover">

    <div class="p-3 sm:p-5 text-center">

        <h3 class="font-semibold text-base sm:text-lg mb-2">
            <?= $c[1] ?>
        </h3>

        <a href="<?= BASE_URL ?>category.php?slug=<?= $c[0] ?>"
            class="text-blue-600 font-medium hover:underline text-sm sm:text-base">
            Explore →
        </a>

    </div>
</div>
<?php endforeach; ?>

</div>
</section>

<!-- FEATURE STRIP -->
<section class="bg-white border-t border-b py-16">

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-12 text-center px-4">

        <div class="space-y-3 px-4">
            <h3 class="font-bold text-xl text-gray-800">Fast & Clean</h3>
            <p class="text-gray-500 leading-relaxed">
                Optimized for reading experience
            </p>
        </div>

        <div class="space-y-3 px-4">
            <h3 class="font-bold text-xl text-gray-800">Easy Publishing</h3>
            <p class="text-gray-500 leading-relaxed">
                Write and publish instantly
            </p>
        </div>

        <div class="space-y-3 px-4">
            <h3 class="font-bold text-xl text-gray-800">Secure Platform</h3>
            <p class="text-gray-500 leading-relaxed">
                Safe authentication & role-based access
            </p>
        </div>

    </div>

</section>

<!-- FOOTER -->
<footer class="text-center py-8 text-gray-500">
    © <?= date('Y') ?> BlogSphere. All rights reserved.
</footer>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const categoryBtn = document.querySelector("[data-category-btn]");
    const categoryMenu = document.getElementById("categoryDropdown");

    const userBtn = document.querySelector("[data-user-btn]");
    const userMenu = document.getElementById("userMenu");

    const mobileBtn = document.getElementById("mobileMenuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    const iconOpen = document.getElementById("iconOpen");
    const iconClose = document.getElementById("iconClose");

    function closeMenus() {
        categoryMenu?.classList.add("hidden");
        userMenu?.classList.add("hidden");
    }

    categoryBtn?.addEventListener("click", function(e) {
        e.stopPropagation();
        userMenu?.classList.add("hidden");
        categoryMenu?.classList.toggle("hidden");
    });

    userBtn?.addEventListener("click", function(e) {
        e.stopPropagation();
        categoryMenu?.classList.add("hidden");
        userMenu?.classList.toggle("hidden");
    });

    mobileBtn?.addEventListener("click", function(e) {
        e.stopPropagation();
        mobileMenu?.classList.toggle("hidden");
        iconOpen?.classList.toggle("hidden");
        iconClose?.classList.toggle("hidden");
    });

    document.addEventListener("click", function() {
        closeMenus();
    });

    categoryMenu?.addEventListener("click", function(e) {
        e.stopPropagation();
    });

    userMenu?.addEventListener("click", function(e) {
        e.stopPropagation();
    });

    mobileMenu?.addEventListener("click", function(e) {
        e.stopPropagation();
    });

});
</script>

</body>
</html>