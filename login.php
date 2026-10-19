<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </nav>
    </div>
</header>

<main>
    <section class="card form-card">
        <h1>Member Login</h1>

        <div id="message" class="notice" hidden></div>

        <form id="login-form">
            <label for="email">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                required
            >

            <label for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
            >

            <p>
                <button type="submit">Log In</button>
            </p>
        </form>

        <p>
            Don't have an account?
            <a href="register.php">Register here</a>.
        </p>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('login-form')
        .addEventListener('submit', async event => {
            event.preventDefault();

            const form = new FormData(event.currentTarget);

            try {
                const result = await api('login.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        email: form.get('email'),
                        password: form.get('password')
                    })
                });

                setSession(result);

                showMessage('Login successful.', 'success');

                window.location.href = 'dashboard.php';

            } catch (error) {
                showMessage(error.message, 'error');
            }
        });
});
</script>

</body>
</html>
