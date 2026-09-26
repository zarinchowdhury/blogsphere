<?php require_once '../config/app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - BlogSphere</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-500 px-4 py-10">

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 sm:p-10">

    <div class="text-center mb-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Welcome back</h2>
        <p class="text-gray-500 text-sm mt-1">Log in to continue to BlogSphere</p>
    </div>

    <?php if (!empty($_GET['registered'])): ?>
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
            Account created! You can log in now.
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['timeout'])): ?>
        <div class="mb-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm px-4 py-3">
            You were logged out due to inactivity.
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login_process.php" class="space-y-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                placeholder="you@example.com">
        </div>

               <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <input type="password" id="loginPassword" name="password" required
                    class="w-full px-4 py-3 pr-11 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="Your password">
                <button type="button" onclick="togglePassword('loginPassword', this)"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <svg class="eye-open h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg class="eye-closed h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.066 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit"
            class="w-full bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white font-semibold py-3 rounded-lg hover:opacity-90 active:scale-[0.99] transition">
            Log in
        </button>

    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account?
        <a href="register.php" class="text-indigo-600 font-medium hover:underline">Create one</a>
    </p>

</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.querySelector('.eye-open').classList.toggle('hidden', show);
    btn.querySelector('.eye-closed').classList.toggle('hidden', !show);
}
</script>

</body>
</html>