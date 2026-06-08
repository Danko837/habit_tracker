<?php
$title = 'Registrácia - Habit Tracker';
$active = 'register';
$contentView = __FILE__;
if (!isset($renderedRegister)) {
    $renderedRegister = true;
    require __DIR__ . '/layout.php';
    return;
}
?>
<section class="form-card">
    <h1>Vytvoriť účet</h1>
    <?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="POST" action="<?= url('/register') ?>" class="form">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>Meno <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" required autofocus></label>
        <label>E-mail <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required></label>
        <label>Heslo <input type="password" name="password" minlength="8" required></label>
        <label>Potvrdenie hesla <input type="password" name="password_confirm" minlength="8" required></label>
        <button class="btn btn-primary full" type="submit">Zaregistrovať sa</button>
    </form>
    <p class="muted center">Už máš účet? <a href="<?= url('/login') ?>">Prihlás sa</a></p>
</section>
