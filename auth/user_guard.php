<?php

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /BlogSphere/auth/login.php");
        exit;
    }
}