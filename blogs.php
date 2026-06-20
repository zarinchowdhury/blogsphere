<?php
require_once 'config/app.php';

$stmt = $conn->prepare("
    SELECT
        p.*,
        u.username,
        c.name AS category_name
    FROM posts p
    LEFT JOIN users u
        ON p.author_id = u.user_id
    LEFT JOIN categories c
        ON p.category_id = c.category_id
    WHERE p.status = 'published'
    ORDER BY p.created_at DESC
");

$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Explore Blogs | BlogSphere</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-50">

<!-- NAVBAR -->
<header class="bg-white shadow-sm sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <a href="/BlogSphere/index.php"
           class="text-2xl font-bold text-blue-600">
            BlogSphere
        </a>

        <div class="flex items-center gap-4">

            <a href="/BlogSphere/index.php"
               class="text-gray-600 hover:text-blue-600">
                Home
            </a>

            <a href="/BlogSphere/about.php"
               class="text-gray-600 hover:text-blue-600">
                About
            </a>

        </div>

    </div>

</header>

<!-- HERO -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">

    <div class="max-w-7xl mx-auto px-6 py-16 text-center">

        <h1 class="text-5xl font-bold mb-4">
            Explore Blogs
        </h1>

        <p class="text-blue-100 text-lg max-w-2xl mx-auto">
            Discover stories, insights, technology trends,
            travel experiences and business ideas from our community.
        </p>

    </div>

</section>

<!-- BLOGS -->
<section class="max-w-7xl mx-auto px-6 py-12">

    <?php if (!empty($posts)): ?>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php foreach ($posts as $post): ?>

                <?php
                $image = !empty($post['image_url'])
                    ? $post['image_url']
                    : 'https://via.placeholder.com/800x500?text=BlogSphere';
                ?>

                <a
                    href="/BlogSphere/post.php?slug=<?= urlencode($post['slug']) ?>"
                    class="group bg-white rounded-2xl overflow-hidden shadow hover:shadow-2xl hover:-translate-y-1 transition duration-300 block"
                >

                    <!-- IMAGE -->
                    <div class="overflow-hidden">

                        <img
                            src="<?= htmlspecialchars($image) ?>"
                            alt="<?= htmlspecialchars($post['title']) ?>"
                            class="w-full h-56 object-cover group-hover:scale-105 transition duration-500"
                            onerror="this.src='https://via.placeholder.com/800x500?text=BlogSphere';"
                        >

                    </div>

                    <!-- CONTENT -->
                    <div class="p-6">

                        <!-- CATEGORY -->
                        <span class="inline-block px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full mb-3">

                            <?= htmlspecialchars($post['category_name'] ?? 'General') ?>

                        </span>

                        <!-- TITLE -->
                        <h2 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition">

                            <?= htmlspecialchars($post['title']) ?>

                        </h2>

                        <!-- AUTHOR -->
                        <div class="text-sm text-gray-500 mb-4">

                            By
                            <span class="font-medium">
                                <?= htmlspecialchars($post['username'] ?? 'Unknown') ?>
                            </span>

                            •

                            <?= date('M d, Y', strtotime($post['created_at'])) ?>

                        </div>

                        <!-- DESCRIPTION -->
                        <p class="text-gray-600 leading-relaxed mb-5">

                            <?= htmlspecialchars(
                                substr(
                                    strip_tags(
                                        $post['description']
                                        ?: $post['content']
                                    ),
                                    0,
                                    140
                                )
                            ) ?>...

                        </p>

                        <!-- FOOTER -->
                        <div class="flex justify-between items-center">

                            <span class="text-blue-600 font-semibold">
                                Read Article →
                            </span>

                            <span class="text-sm text-gray-400">
                                👁 <?= (int)$post['views'] ?>
                            </span>

                        </div>

                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="bg-white rounded-2xl shadow p-12 text-center">

            <h2 class="text-3xl font-bold mb-3">
                No Blogs Available
            </h2>

            <p class="text-gray-500 mb-6">
                There are currently no published blogs.
            </p>

            <a href="/BlogSphere/index.php"
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">

                Back Home

            </a>

        </div>

    <?php endif; ?>

</section>

<!-- FOOTER -->
<footer class="bg-white border-t mt-12">

    <div class="max-w-7xl mx-auto px-6 py-8 text-center">

        <p class="text-gray-500">
            © <?= date('Y') ?> BlogSphere. All rights reserved.
        </p>

    </div>

</footer>

</body>
</html>