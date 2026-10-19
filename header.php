<?php
$pageTitle = $pageTitle ?? 'FightClubSA';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | FightClubSA</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php">
        <span class="brand-icon">FC</span>
        <span>FightClub<span class="accent">SA</span></span>
    </a>

    <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="classes.php">Classes</a>
        <a href="membership.php">Membership</a>
        <a href="dashboard.php" data-member-link>Dashboard</a>
        <a href="login.php" data-guest-link>Login</a>
        <a href="register.php" class="nav-cta" data-guest-link>Join Now</a>
        <button type="button" class="nav-button" data-logout hidden>Logout</button>
    </nav>
</header>

<main class="container"></main>