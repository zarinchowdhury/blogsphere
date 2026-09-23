<<<<<<< HEAD
<?php include "../config/db.php"; ?>

<?php
if(isset($_GET['token'])){

    $token = $_GET['token'];

    $sql = "SELECT * FROM email_verifications WHERE token='$token'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        $conn->query("UPDATE email_verifications 
                    SET is_verified=1, verified_at=NOW()
                    WHERE verification_id=".$row['verification_id']);

        echo "Email verified successfully!";
    } else {
        echo "Invalid token";
    }
}
?>
=======
<?php
require_once '../config/app.php';

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("
    SELECT * FROM users 
    WHERE verification_token=? 
    AND token_expiry > NOW()
");

$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Invalid or expired token");
}

$stmt = $conn->prepare("
    UPDATE users 
    SET is_verified=1, verification_token=NULL, token_expiry=NULL
    WHERE user_id=?
");

$stmt->execute([$user['user_id']]);

echo "Email verified successfully. <a href='login.php'>Login</a>";
>>>>>>> 306c5fe61ea6d51e64307b22ebf557b95835a7e9
