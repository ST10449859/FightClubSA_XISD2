<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Classes</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="bookings.php">Bookings</a>
            <a href="login.php">Login</a>
            <button type="button" data-logout>Logout</button>
        </nav>
    </div>
</header>

<main>
    <h1>Upcoming Classes</h1>

    <p class="muted">
        Browse available training sessions and reserve your place.
    </p>

    <div id="message" class="notice" hidden></div>
    <div id="classes" class="grid">Loading classes...</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('classes');

    try {
        const result = await api('classes.php');

        if (!result.classes.length) {
            container.textContent =
                'No upcoming active classes are currently available.';
            return;
        }

        container.innerHTML = result.classes.map(c => `
            <article class="card">
                <h2>${escapeHtml(c.class_name)}</h2>

                <p>${escapeHtml(c.description || 'Training session')}</p>

                <p>
                    <strong>Date:</strong>
                    ${escapeHtml(c.class_date)}
                </p>

                <p>
                    <strong>Time:</strong>
                    ${escapeHtml(c.start_time)} -
                    ${escapeHtml(c.end_time)}
                </p>

                <p>
                    <strong>Trainer:</strong>
                    ${escapeHtml(c.trainer_name || 'To be announced')}
                </p>

                <p>
                    <strong>Spaces available:</strong>
                    ${escapeHtml(c.spaces_available)}
                </p>

                <button
                    data-book="${escapeHtml(c.class_id)}"
                    ${Number(c.spaces_available) < 1 ? 'disabled' : ''}
                >
                    Book Class
                </button>
            </article>
        `).join('');

        container.querySelectorAll('[data-book]').forEach(button => {
            button.addEventListener('click', async () => {
                if (!getToken()) {
                    window.location.href = 'login.php';
                    return;
                }

                button.disabled = true;

                try {
                    const result = await api('bookings.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            action: 'book',
                            class_id: Number(button.dataset.book)
                        })
                    });

                    showMessage(result.message, 'success');
                    window.location.reload();

                } catch (error) {
                    showMessage(error.message, 'error');
                    button.disabled = false;
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
