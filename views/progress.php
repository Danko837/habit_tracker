<?php
$title = 'Pokrok - Habit Tracker';
$active = 'progress';
$contentView = __FILE__;
if (!isset($renderedProgress)) {
    $renderedProgress = true;
    require __DIR__ . '/layout.php';
    return;
}
?>
<section class="page-head">
    <div><span class="eyebrow">Štatistiky</span><h1>Pokrok</h1><p>Jednoduchý prehľad tvojich návykov.</p></div>
</section>
<section class="grid three">
    <article class="stat"><strong><?= (int)$stats['total'] ?></strong><span>všetkých návykov</span></article>
    <article class="stat"><strong><?= (int)$stats['active'] ?></strong><span>aktívnych návykov</span></article>
    <article class="stat"><strong><?= (int)$stats['completed_today'] ?></strong><span>splnených dnes</span></article>
</section>
<section class="card">
    <h2>Posledné splnenia</h2>
    <?php if (empty($logs)): ?>
        <p>Zatiaľ nie sú žiadne záznamy.</p>
    <?php else: ?>
        <table class="table"><thead><tr><th>Dátum</th><th>Počet splnení</th></tr></thead><tbody>
        <?php foreach ($logs as $log): ?><tr><td><?= e($log['log_date']) ?></td><td><?= (int)$log['completed'] ?></td></tr><?php endforeach; ?>
        </tbody></table>
    <?php endif; ?>
</section>
