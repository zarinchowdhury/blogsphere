<?php require_once 'config/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - BlogSphere</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f4f6f9;
    color:#333;
}

.container{
    max-width:1000px;
    margin:50px auto;
    padding:20px;
}

.card{
    background:#fff;
    padding:40px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

h1{
    margin-bottom:20px;
    color:#111827;
}

h2{
    margin-top:30px;
    margin-bottom:15px;
    color:#111827;
}

p{
    line-height:1.8;
    color:#555;
}

.contact-box{
    margin-top:20px;
    padding:20px;
    background:#f9fafb;
    border-radius:10px;
}

.contact-box p{
    margin:10px 0;
}

.back-home{
    display:inline-block;
    margin-top:25px;
    padding:12px 20px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
}

.back-home:hover{
    background:#1d4ed8;
}
</style>
</head>
<body>

<div class="container">

    <div class="card">

        <h1>About BlogSphere</h1>

        <p>
            BlogSphere is a modern blogging platform where writers,
            creators, and readers can connect through meaningful content.
            Our mission is to provide a simple, secure, and engaging space
            for sharing ideas, stories, tutorials, and experiences.
        </p>

        <h2>Our Vision</h2>

        <p>
            We aim to build a community-driven platform that empowers
            individuals to express themselves and discover valuable content
            from around the world.
        </p>

        <h2>Contact Information</h2>

        <div class="contact-box">
            <p><strong>Email:</strong> support@blogsphere.com</p>
            <p><strong>Phone:</strong> +880 1XXX-XXXXXX</p>
            <p><strong>Location:</strong> Dhaka, Bangladesh</p>
        </div>

        <a href="index.php" class="back-home">Back to Home</a>

    </div>

</div>

</body>
</html>