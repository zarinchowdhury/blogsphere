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