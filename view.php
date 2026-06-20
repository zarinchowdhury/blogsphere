<?php include "../includes/header.php"; ?>
<?php include "../config/db.php"; ?>

<div class="col-md-12 p-4">

<?php
$id = $_GET['id'];

$sql = "SELECT * FROM posts WHERE post_id=$id";
$result = $conn->query($sql);
$post = $result->fetch_assoc();
?>

<h2><?= $post['title']; ?></h2>
<p><?= $post['content']; ?></p>

</div>

<?php include "../includes/footer.php"; ?>