<?php
$active = 'habits';
$contentView = __FILE__;
if (!isset($renderedHabitForm)) {
    $renderedHabitForm = true;
    require __DIR__ . '/../layout.php';
    return;
}
?>
<section class="form-card wide">
    <h1><?= e($title) ?></h1>
    <?php if (!empty($error)): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="POST" action="<?= e($action) ?>" class="form">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <label>Názov návyku <input type="text" name="title" value="<?= e($habit['title'] ?? '') ?>" required></label>
        <label>Popis <textarea name="description" rows="4"><?= e($habit['description'] ?? '') ?></textarea></label>
        <div class="form-grid">
            <label>Frekvencia
                <select name="frequency">
                    <?php foreach (['daily' => 'Denne', 'weekly' => 'Týždenne', 'custom' => 'Vlastné'] as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= ($habit['frequency'] ?? '') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Cieľový počet <input type="number" name="target_count" min="1" max="100" value="<?= e((string)($habit['target_count'] ?? 1)) ?>"></label>
            <label>Dátum začiatku <input type="date" name="start_date" value="<?= e($habit['start_date'] ?? date('Y-m-d')) ?>"></label>
        </div>
        <label class="checkbox"><input type="checkbox" name="is_active" value="1" <?= (int)($habit['is_active'] ?? 1) === 1 ? 'checked' : '' ?>> Aktívny návyk</label>
        <div class="actions"><button class="btn btn-primary" type="submit"><?= e($button) ?></button><a class="btn btn-light" href="<?= url('/habits') ?>">Späť</a></div>
    </form>
</section>
