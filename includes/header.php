<?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin/admin_dashboard.php">Admin Dashboard</a>
<?php endif; ?>