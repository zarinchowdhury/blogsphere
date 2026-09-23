<div class="col-md-3 bg-light p-3">

<h5>Categories</h5>
<ul class="list-group">

<?php
include "config/db.php";

$result = $conn->query("SELECT * FROM categories");
while($row = $result->fetch_assoc()):
?>
<li class="list-group-item">
    <?= $row['name']; ?>
</li>
<?php endwhile; ?>

</ul>

<hr>

<h5>Recent Posts</h5>
<ul class="list-group">
<?php
$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
while($p = $posts->fetch_assoc()):
?>
<li class="list-group-item">
    <a href="/blog/posts/view.php?id=<?= $p['post_id']; ?>">
        <?= $p['title']; ?>
    </a>
</li>
<?php endwhile; ?>
</ul>

</div>