<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Notifications</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="settings.php">Settings</a>
            <button type="button" data-logout>Logout</button>
        </nav>
    </div>
</header>

<main>
    <h1>My Notifications</h1>

    <div id="message" class="notice" hidden></div>
    <div id="notifications" class="grid">Loading notifications...</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    if (!getToken()) {
        window.location.href = 'login.php';
        return;
    }

    const container = document.getElementById('notifications');

    try {
        const result = await api('notifications.php');

        if (!result.notifications.length) {
            container.textContent = 'You have no notifications.';
            return;
        }

        container.innerHTML = result.notifications.map(n => `
            <article class="card">
                <h2>${escapeHtml(n.title)}</h2>
                <p>${escapeHtml(n.message)}</p>
                <p class="muted">${escapeHtml(n.created_at)}</p>
                <p>
                    Status:
                    ${Number(n.is_read) === 1 ? 'Read' : 'Unread'}
                </p>

                ${Number(n.is_read) === 0
                    ? `<button data-read="${escapeHtml(n.id)}">
                           Mark as Read
                       </button>`
                    : ''}
            </article>
        `).join('');

        container.querySelectorAll('[data-read]').forEach(button => {
            button.addEventListener('click', async () => {
                try {
                    const result = await api('notifications.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            notification_id: Number(button.dataset.read)
                        })
                    });

                    showMessage(result.message, 'success');
                    window.location.reload();

                } catch (error) {
                    showMessage(error.message, 'error');
                }
            });
        });

    } catch (error) {
        container.textContent = '';
        showMessage(error.message, 'error');
    }
});
</script>

</body>
</html>
