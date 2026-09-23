<?php
$cats = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bg-gray-50 border-t">
    <div class="max-w-6xl mx-auto px-4 py-2 flex gap-4 overflow-x-auto">

        <a href="index.php" class="text-sm text-gray-600">All</a>

        <?php foreach ($cats as $cat): ?>
            <a href="?category=<?= $cat['slug'] ?>"
            class="text-sm text-gray-600 hover:text-blue-600">
                <?= $cat['name'] ?>
            </a>
        <?php endforeach; ?>

    </div>
</div>