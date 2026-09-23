<?php include "../config/db.php"; ?>

<form method="POST">
    <input name="username" placeholder="Username" class="form-control"><br>
    <input name="email" placeholder="Email" class="form-control"><br>
    <input name="password" type="password" placeholder="Password" class="form-control"><br>
    <button name="register" class="btn btn-success">Register</button>
</form>

<?php
if(isset($_POST['register'])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $conn->query("INSERT INTO users(username,email,password_hash)
                VALUES('$username','$email','$password')");

    $user_id = $conn->insert_id;

    $token = bin2hex(random_bytes(16));

    $conn->query("INSERT INTO email_verifications(user_id,verification_token)
                VALUES('$user_id','$token')");

    echo "Registered! Verify email token: $token";
}
?>