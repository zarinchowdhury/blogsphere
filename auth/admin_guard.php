<?php

require_once '../config/app.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
} {

    if (!isset($_SESSION['user_id'])) {
<<<<<<< HEAD
        header("Location: " . BASE_URL . "auth/login.php");
=======
        header("Location: /BlogSphere/auth/login.php");
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9
        exit;
    }

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("Access denied. Admins only.");
    }
}