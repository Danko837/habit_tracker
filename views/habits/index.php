<?php
$title = 'Moje návyky - Habit Tracker';
$active = 'habits';
$contentView = __FILE__;
if (!isset($renderedHabitsIndex)) {
    $renderedHabitsIndex = true;
    require __DIR__ . '/../layout.php';
    return;
}
?>
<section class="page-head">
    <div>
        <span class="eyebrow">Návyky</span>
        <h1>Moje návyky</h1>
        <p>Tu spravuješ svoje návyky a označuješ ich denné splnenie.</p>
    </div>
    <a class="btn btn-primary" href="<?= url('/habits/create') ?>">+ Pridať návyk</a>
</section>

<?php if (empty($habits)): ?>
    <div class="card center"><h2>Zatiaľ nemáš žiadny návyk</h2><p>Vytvor prvý návyk a začni sledovať pokrok.</p><a class="btn btn-primary" href="<?= url('/habits/create') ?>">Vytvoriť návyk</a></div>
<?php else: ?>
    <div class="habit-list">
        <?php foreach ($habits as $habit): ?>
            <article class="habit-card <?= (int)$habit['is_active'] === 0 ? 'disabled' : '' ?>">
                <div>
                    <h3><?= e($habit['title']) ?></h3>
                    <p><?= e($habit['description'] ?: 'Bez popisu') ?></p>
                    <div class="meta">
                        <span><?= e($habit['frequency']) ?></span>
                        <span>Cieľ: <?= (int)$habit['target_count'] ?>x</span>
                        <span>Splnené: <?= (int)($habit['completed_count'] ?? 0) ?>x</span>
                        <?php if (!empty($habit['last_completed'])): ?><span>Naposledy: <?= e($habit['last_completed']) ?></span><?php endif; ?>
                    </div>
                </div>
                <div class="habit-actions">
                    <form method="POST" action="<?= url('/habits/toggle') ?>?id=<?= (int)$habit['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                        <button class="btn <?= (int)($habit['completed_today'] ?? 0) === 1 ? 'btn-success' : 'btn-primary' ?>" type="submit">
                            <?= (int)($habit['completed_today'] ?? 0) === 1 ? 'Dnes splnené' : 'Splniť dnes' ?>
                        </button>
                    </form>
                    <a class="btn btn-light" href="<?= url('/habits/edit') ?>?id=<?= (int)$habit['id'] ?>">Upraviť</a>
                    <form method="POST" action="<?= url('/habits/delete') ?>?id=<?= (int)$habit['id'] ?>" onsubmit="return confirm('Naozaj vymazať tento návyk?');">
                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                        <button class="btn btn-danger" type="submit">Vymazať</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
