<?php
require_once 'config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id = $_SESSION['user_id'];

$posts = $conn->prepare("
    SELECT * FROM posts
    WHERE author_id = ?
    ORDER BY created_at DESC
");

$posts->execute([$id]);
$posts = $posts->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<div class="w-64 bg-gray-900 text-white min-h-screen p-5">

    <h2 class="text-xl font-bold mb-6">User Panel</h2>

    <a href="index.php" class="block py-2">Home</a>
    <a href="post_create.php" class="block py-2">Create Post</a>
    <a href="auth/logout.php" class="block py-2 text-red-400">Logout</a>

</div>

<!-- MAIN -->
<div class="flex-1 p-8">

<h1 class="text-2xl font-bold mb-6">My Posts</h1>

<table class="w-full bg-white shadow">

<tr>
<th>Title</th>
<th>Status</th>
<th>Approval</th>
<th>Date</th>
</tr>

<?php foreach ($posts as $p): ?>
<tr class="border-b">
<td><?= $p['title'] ?></td>
<td><?= $p['status'] ?></td>
<td><?= $p['approval_status'] ?></td>
<td><?= $p['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>

</body>
</html>