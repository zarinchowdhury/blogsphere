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
        header("Location: /BlogSphere/auth/login.php");
        exit;
    }

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("Access denied. Admins only.");
    }
}