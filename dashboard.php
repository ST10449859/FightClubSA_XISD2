<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="classes.php">Classes</a>
            <a href="bookings.php">Bookings</a>
            <a href="membership.php">Membership</a>
            <button type="button" data-logout>Logout</button>
        </nav>
    </div>
</header>

<main>
    <h1>Member Dashboard</h1>

    <div id="message" class="notice" hidden></div>

    <p id="welcome" class="muted">Loading account information...</p>

    <section class="grid">
        <article class="card">
            <h2>Training Classes</h2>
            <p>Find a class and reserve your place.</p>
            <a class="button" href="classes.php">Browse Classes</a>
        </article>

        <article class="card">
            <h2>My Bookings</h2>
            <p>Review your class reservations.</p>
            <a class="button" href="bookings.php">View Bookings</a>
        </article>

        <article class="card">
            <h2>Membership</h2>
            <p>View your current membership.</p>
            <a class="button" href="membership.php">View Membership</a>
        </article>

        <article class="card">
            <h2>Payments</h2>
            <p>Review your payment records.</p>
            <a class="button" href="payments.php">View Payments</a>
        </article>

        <article class="card">
            <h2>Notifications</h2>
            <p>View your account notifications.</p>
            <a class="button" href="notifications.php">View Notifications</a>
        </article>

        <article class="card">
            <h2>Settings</h2>
            <p>Manage your preferences.</p>
            <a class="button" href="settings.php">Manage Settings</a>
        </article>
    </section>

    <p id="summary" class="muted"></p>
</main>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    if (!getToken()) {
        window.location.href = 'login.php';
        return;
    }

    const user = getUser();

    document.getElementById('welcome').textContent =
        user ? 'Welcome, ' + user.full_name + '!' : 'Welcome back!';

    try {
        const [bookings, notifications] = await Promise.all([
            api('bookings.php'),
            api('notifications.php')
        ]);

        document.getElementById('summary').textContent =
            'Booking records: ' + bookings.count +
            ' | Notifications: ' + notifications.count;

    } catch (error) {
        showMessage(error.message, 'error');
    }
});
</script>

</body>
</html>
