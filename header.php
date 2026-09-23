<<<<<<< HEAD
<!DOCTYPE html>
<html>
<head>
    <title>Blog System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/blog/index.php">My Blog</a>
  </div>
</nav>

<div class="container-fluid">
<div class="row">
=======
<?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin/admin_dashboard.php">Admin Dashboard</a>
<?php endif; ?>
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9
