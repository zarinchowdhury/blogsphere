<?php
require_once '../config/app.php';
require_once '../includes/auth_guard.php';
require_once '../includes/csrf.php';

requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!verifyCSRF($_POST['csrf_token'])) {
        die("CSRF validation failed");
    }

    $username = trim($_POST['username']);

    // avatar upload
    $avatarName = $user['avatar'];

    if (!empty($_FILES['avatar']['name'])) {

        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Invalid image type";
        } else {

            $avatarName = time() . "_" . $_FILES['avatar']['name'];

            move_uploaded_file(
                $_FILES['avatar']['tmp_name'],
                "../assets/uploads/profiles/" . $avatarName
            );
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("
            UPDATE users
            SET username = ?, avatar = ?
            WHERE id = ?
        ");

        $stmt->execute([$username, $avatarName, $user_id]);

        $_SESSION['username'] = $username;

        header("Location: index.php");
        exit;
    }
}

$token = generateCSRF();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Edit Profile</h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-600 p-2 mb-3">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="csrf_token" value="<?= $token ?>">

        <input type="text" name="username"
            value="<?= htmlspecialchars($user['username']) ?>"
            class="w-full p-2 border mb-3">

        <input type="file" name="avatar"
            class="w-full p-2 border mb-3">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Save Changes
        </button>

    </form>

</div>

</body>
</html>