<?php
$title = 'Prehľad - Habit Tracker';
$active = 'dashboard';
$contentView = __FILE__;
if (!isset($renderedDashboard)) {
    $renderedDashboard = true;
    require __DIR__ . '/layout.php';
    return;
}
?>
<section class="page-head">
    <div>
        <span class="eyebrow">Prehľad</span>
        <h1>Ahoj, <?= e($_SESSION['user_name'] ?? 'používateľ') ?></h1>
        <p>Sleduj svoje návyky a zapisuj splnenie každý deň.</p>
    </div>
    <a class="btn btn-primary" href="<?= url('/habits/create') ?>">+ Nový návyk</a>
</section>

<section class="grid three">
    <article class="card"><h3>Moje návyky</h3><p>Zobraz zoznam, uprav alebo vymaž návyk.</p><a class="btn btn-light" href="<?= url('/habits') ?>">Otvoriť</a></article>
    <article class="card"><h3>Pokrok</h3><p>Prehľad počtu aktívnych a splnených návykov.</p><a class="btn btn-light" href="<?= url('/progress') ?>">Pozrieť</a></article>
    <article class="card"><h3>Dnešná úloha</h3><p>Označ, čo si dnes splnil, aby si držal sériu.</p><a class="btn btn-light" href="<?= url('/habits') ?>">Zaznamenať</a></article>
</section>
