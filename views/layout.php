<?php
$title = $title ?? 'Habit Tracker';
$active = $active ?? '';
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= url('/assets/style.css') ?>">
</head>
<body>
<nav class="topbar">
    <div class="container nav-inner">
        <a href="<?= url('/') ?>" class="logo">✓ Habit Tracker</a>
        <ul class="nav-links">
            <?php if (isLoggedIn()): ?>
                <li><a class="<?= $active === 'dashboard' ? 'active' : '' ?>" href="<?= url('/dashboard') ?>">Prehľad</a></li>
                <li><a class="<?= $active === 'habits' ? 'active' : '' ?>" href="<?= url('/habits') ?>">Návyky</a></li>
                <li><a class="<?= $active === 'progress' ? 'active' : '' ?>" href="<?= url('/progress') ?>">Pokrok</a></li>
                <li>
                    <form method="POST" action="<?= url('/logout') ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                        <button class="btn btn-light" type="submit">Odhlásiť</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a class="<?= $active === 'login' ? 'active' : '' ?>" href="<?= url('/login') ?>">Prihlásenie</a></li>
                <li><a class="btn btn-primary" href="<?= url('/register') ?>">Registrácia</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<main class="main">
    <div class="container">
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php require $contentView; ?>
    </div>
</main>

<footer class="footer">
    <div class="container">&copy; <?= date('Y') ?> Habit Tracker</div>
</footer>
</body>
</html>
