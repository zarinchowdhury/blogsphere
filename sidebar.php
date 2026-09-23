<<<<<<< HEAD
<div class="col-md-3 bg-light p-3">

<h5>Categories</h5>
<ul class="list-group">

<?php
include "config/db.php";

$result = $conn->query("SELECT * FROM categories");
while($row = $result->fetch_assoc()):
?>
<li class="list-group-item">
    <?= $row['name']; ?>
</li>
<?php endwhile; ?>

</ul>

<hr>

<h5>Recent Posts</h5>
<ul class="list-group">
<?php
$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
while($p = $posts->fetch_assoc()):
?>
<li class="list-group-item">
    <a href="/blog/posts/view.php?id=<?= $p['post_id']; ?>">
        <?= $p['title']; ?>
    </a>
</li>
<?php endwhile; ?>
</ul>
=======
<div class="w-64 bg-gray-900 text-white min-h-screen p-5 fixed">

    <h2 class="text-xl font-bold mb-6">BlogSphere</h2>

    <a href="<?= BASE_URL ?>index.php" class="block py-2">🏠 Home</a>

    <a href="<?= BASE_URL ?>auth/dashboard.php" class="block py-2">📊 Dashboard</a>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="<?= BASE_URL ?>admin/admin_dashboard.php" class="block py-2">🛠 Admin Panel</a>
        <a href="<?= BASE_URL ?>admin/posts.php" class="block py-2">📝 Manage Posts</a>
        <a href="<?= BASE_URL ?>admin/analytics.php" class="block py-2">📈 Analytics</a>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>post_create.php" class="block py-2">✍ Create Post</a>

    <a href="<?= BASE_URL ?>auth/logout.php" class="block py-2 text-red-400">🚪 Logout</a>
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9

</div>