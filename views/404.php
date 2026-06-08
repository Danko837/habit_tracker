<?php
$title = 'Stránka nenájdená';
$contentView = __FILE__;
if (!isset($rendered404)) {
    $rendered404 = true;
    require __DIR__ . '/layout.php';
    return;
}
?>
<section class="card center">
    <h1>404</h1>
    <p>Táto stránka neexistuje.</p>
    <a class="btn btn-primary" href="<?= url('/dashboard') ?>">Späť</a>
</section>
