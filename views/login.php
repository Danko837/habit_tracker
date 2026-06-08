<?php
$title = 'Prihlásenie - Habit Tracker';
$active = 'login';
$contentView = __FILE__;
if (!isset($renderedLogin)) {
    $renderedLogin = true;
    require __DIR__ . '/layout.php';
    return;
}
?>
<section class="form-card">
    <h1>Prihlásenie</h1>
    <?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="POST" action="<?= url('/login') ?>" class="form">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>E-mail <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required autofocus></label>
        <label>Heslo <input type="password" name="password" required></label>
        <button class="btn btn-primary full" type="submit">Prihlásiť sa</button>
    </form>
    <p class="muted center">Nemáš účet? <a href="<?= url('/register') ?>">Zaregistruj sa</a></p>
</section>
