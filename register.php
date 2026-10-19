<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Register</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>

<main>
    <section class="card form-card">
        <h1>Create an Account</h1>

        <div id="message" class="notice" hidden></div>

        <form id="register-form">
            <label for="full_name">Full name</label>
            <input
                id="full_name"
                name="full_name"
                maxlength="100"
                autocomplete="name"
                required
            >

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
                minlength="8"
                autocomplete="new-password"
                required
            >

            <small>Password must contain at least 8 characters.</small>

            <p>
                <button type="submit">Create Account</button>
            </p>
        </form>

        <p>
            Already registered?
            <a href="login.php">Log in</a>.
        </p>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('register-form')
        .addEventListener('submit', async event => {
            event.preventDefault();

            const form = new FormData(event.currentTarget);

            try {
                const result = await api('register.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        full_name: form.get('full_name'),
                        email: form.get('email'),
                        password: form.get('password')
                    })
                });

                showMessage(
                    result.message + ' Redirecting to login...',
                    'success'
                );

                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 1000);

            } catch (error) {
                showMessage(error.message, 'error');
            }
        });
});
</script>

</body>
</html>
