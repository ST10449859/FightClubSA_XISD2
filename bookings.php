<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | My Bookings</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="classes.php">Classes</a>
            <button type="button" data-logout>Logout</button>
        </nav>
    </div>
</header>

<main>
    <h1>My Bookings</h1>

    <div id="message" class="notice" hidden></div>
    <div id="bookings" class="grid">Loading bookings...</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    if (!getToken()) {
        window.location.href = 'login.php';
        return;
    }

    const container = document.getElementById('bookings');

    try {
        const result = await api('bookings.php');

        if (!result.bookings.length) {
            container.textContent =
                'You do not have any bookings yet.';
            return;
        }

        container.innerHTML = result.bookings.map(b => `
            <article class="card">
                <h2>${escapeHtml(b.class_name)}</h2>

                <p>
                    Date: ${escapeHtml(b.class_date)}
                </p>

                <p>
                    Time: ${escapeHtml(b.start_time)}
                </p>

                <p>
                    Trainer:
                    ${escapeHtml(b.trainer_name || 'To be announced')}
                </p>

                <p>
                    Status:
                    <span class="status">${escapeHtml(b.status)}</span>
                </p>

                ${b.status === 'booked'
                    ? `<button data-cancel="${escapeHtml(b.booking_id)}">
                           Cancel Booking
                       </button>`
                    : ''}
            </article>
        `).join('');

        container.querySelectorAll('[data-cancel]').forEach(button => {
            button.addEventListener('click', async () => {
                if (!confirm('Are you sure you want to cancel this booking?')) {
                    return;
                }

                try {
                    const result = await api('bookings.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            action: 'cancel',
                            booking_id: Number(button.dataset.cancel)
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
