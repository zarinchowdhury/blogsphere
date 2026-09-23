<?php

require_once '../config/app.php';
require_once '../includes/admin_guard.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<div class="flex">

    <?php include '../includes/sidebar.php'; ?>

    <div class="ml-64 p-10 w-full">

        <h1 class="text-3xl font-bold">
            Welcome <?= htmlspecialchars($_SESSION['username']) ?>
        </h1>

        <p class="text-gray-600 mt-2">
            Role: <?= $_SESSION['role'] ?>
        </p>

    </div>

</div>