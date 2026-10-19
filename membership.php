<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Membership</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="payments.php">Payments</a>
            <button type="button" data-logout>Logout</button>
        </nav>
    </div>
</header>

<main>
    <h1>My Membership</h1>

    <div id="message" class="notice" hidden></div>
    <section id="membership" class="card">
        Loading membership...
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    if (!getToken()) {
        window.location.href = 'login.php';
        return;
    }

    const box = document.getElementById('membership');

    try {
        const result = await api('membership.php');
        const m = result.membership;

        if (!m) {
            box.textContent =
                'No membership is linked to your account. Please contact the gym administrator.';
            return;
        }

        box.innerHTML = `
            <h2>${escapeHtml(m.plan_name)}</h2>

            <p>${escapeHtml(m.plan_description || '')}</p>

            <p>
                <strong>Monthly price:</strong>
                ${money(m.monthly_price)}
            </p>

            <p>
                <strong>Start date:</strong>
                ${escapeHtml(m.start_date)}
            </p>

            <p>
                <strong>End date:</strong>
                ${escapeHtml(m.end_date)}
            </p>

            <p>
                <strong>Status:</strong>
                <span class="status">${escapeHtml(m.status)}</span>
            </p>
        `;

    } catch (error) {
        box.textContent = '';
        showMessage(error.message, 'error');
    }
});
</script>

</body>
</html>
