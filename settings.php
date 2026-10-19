<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Settings</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="notifications.php">Notifications</a>
            <button type="button" data-logout>Logout</button>
        </nav>
    </div>
</header>

<main>
    <h1>Account Settings</h1>

    <div id="message" class="notice" hidden></div>

    <section class="card form-card">
        <form id="settings-form">
            <label for="theme">Theme</label>
            <select id="theme" name="theme">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
            </select>

            <label for="language">Language</label>
            <select id="language" name="language">
                <option value="en">English</option>
                <option value="af">Afrikaans</option>
                <option value="zu">isiZulu</option>
            </select>

            <label for="notifications_enabled">
                <input
                    id="notifications_enabled"
                    type="checkbox"
                    style="width:auto"
                >
                Enable notifications
            </label>

            <p>
                <button type="submit">Save Settings</button>
            </p>
        </form>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    if (!getToken()) {
        window.location.href = 'login.php';
        return;
    }

    const form = document.getElementById('settings-form');
    const notificationCheckbox =
        document.getElementById('notifications_enabled');

    try {
        const result = await api('settings.php');
        const settings = result.settings;

        form.elements.theme.value = settings.theme;
        form.elements.language.value = settings.language;

        notificationCheckbox.checked =
            Number(settings.notifications_enabled) === 1;

        applyTheme(settings.theme);

    } catch (error) {
        showMessage(error.message, 'error');
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();

        try {
            const result = await api('settings.php', {
                method: 'PUT',
                body: JSON.stringify({
                    theme: form.elements.theme.value,
                    language: form.elements.language.value,
                    notifications_enabled:
                        notificationCheckbox.checked ? 1 : 0
                })
            });

            applyTheme(result.settings.theme);

            showMessage(result.message, 'success');

        } catch (error) {
            showMessage(error.message, 'error');
        }
    });
});
</script>

</body>
</html>
