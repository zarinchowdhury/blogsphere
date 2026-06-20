<?php require_once '../config/app.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - BlogSphere</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4f46e5, #9333ea);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 40px;
            width: 350px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
        }

        input:focus {
            border-color: #4f46e5;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4f46e5;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #3730a3;
        }

        .link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .link a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Login</h2>

    <form method="POST" action="login_process.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="link">
        Don't have an account? <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>