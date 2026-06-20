<div class="w-64 bg-gray-900 text-white min-h-screen p-5 fixed">

    <h2 class="text-xl font-bold mb-6">BlogSphere</h2>

    <a href="/BlogSphere/index.php" class="block py-2">🏠 Home</a>

    <a href="/BlogSphere/auth/admin_dashboard.php" class="block py-2">📊 Dashboard</a>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="/BlogSphere/admin/admin_dashboard.php" class="block py-2">🛠 Admin Panel</a>
        <a href="/BlogSphere/admin/posts.php" class="block py-2">📝 Manage Posts</a>
        <a href="/BlogSphere/admin/analytics.php" class="block py-2">📈 Analytics</a>
    <?php endif; ?>

    <a href="/BlogSphere/create-post.php" class="block py-2">✍ Create Post</a>

    <a href="/BlogSphere/auth/logout.php" class="block py-2 text-red-400">🚪 Logout</a>

</div>