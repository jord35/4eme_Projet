<?php
$statusIsAvailable = (bool) ($statusIsAvailable ?? false);
$statusText = (string) ($statusText ?? ($statusIsAvailable ? 'Disponible' : 'Non disponible'));
$statusClassName = 'status ' . ($statusIsAvailable ? 'status--available' : 'status--unavailable');
?>

<span class="<?= htmlspecialchars($statusClassName, ENT_QUOTES, 'UTF-8') ?>">
    <?= htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8') ?>
</span>