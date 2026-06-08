<?php
$title = 'Habit Tracker - Buduj lepšie návyky';
$active = 'home';
$contentView = __FILE__;
if (!isset($renderedHome)) {
    $renderedHome = true;
    require __DIR__ . '/layout.php';
    return;
}
?>
<section class="hero">
    <div>
        <span class="eyebrow">Jednoduchý PHP OOP projekt</span>
        <h1>Habit Tracker</h1>
        <p>Vytváraj návyky, označuj splnenie a sleduj svoj denný pokrok. Projekt obsahuje registráciu, prihlásenie, CRUD a prácu s databázou.</p>
        <div class="actions">
            <a class="btn btn-primary" href="<?= url('/register') ?>">Začať</a>
            <a class="btn btn-light" href="<?= url('/login') ?>">Prihlásiť sa</a>
        </div>
    </div>
    <div class="hero-panel">
        <h2>Čo projekt vie</h2>
        <ul class="check-list">
            <li>Registrácia a login používateľa</li>
            <li>Bezpečné hashovanie hesiel</li>
            <li>CRUD pre návyky</li>
            <li>Označenie splnenia dnešného dňa</li>
            <li>Prehľad štatistík</li>
        </ul>
    </div>
</section>

<section class="grid three">
    <article class="card"><h3>OOP</h3><p>Modely používajú PDO a triedy, controller rieši logiku aplikácie.</p></article>
    <article class="card"><h3>MySQL</h3><p>Tabuľky users, habits a habit_logs sú prepojené cudzími kľúčmi.</p></article>
    <article class="card"><h3>CRUD</h3><p>Používateľ vie návyk vytvoriť, čítať, upraviť aj vymazať.</p></article>
</section>
