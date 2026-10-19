<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FightClubSA | Payments</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>

<header>
    <div class="nav">
        <a class="brand" href="index.php">FIGHTCLUBSA</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="membership.php">Membership</a>
            <button type="button" data-logout>Logout</button>
        </nav>
    </div>
</header>

<main>
    <h1>Payment History</h1>

    <div id="message" class="notice" hidden></div>
    <section id="payments" class="card">
        Loading payment history...
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    if (!getToken()) {
        window.location.href = 'login.php';
        return;
    }

    const box = document.getElementById('payments');

    try {
        const result = await api('payments.php');

        if (!result.payments.length) {
            box.textContent =
                'No payment records were found for your account.';
            return;
        }

        box.innerHTML = `
            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${result.payments.map(p => `
                            <tr>
                                <td>${escapeHtml(p.plan_name)}</td>
                                <td>${money(p.amount)}</td>
                                <td>${escapeHtml(p.status)}</td>
                                <td>${escapeHtml(p.payment_method)}</td>
                                <td>${escapeHtml(p.reference_code)}</td>
                                <td>${escapeHtml(p.created_at)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;

    } catch (error) {
        box.textContent = '';
        showMessage(error.message, 'error');
    }
});
</script>

</body>
</html>
