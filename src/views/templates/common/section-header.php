<?php
$sectionHeaderTitle = (string) ($sectionHeaderTitle ?? '');
$sectionHeaderText = isset($sectionHeaderText) ? trim((string) $sectionHeaderText) : null;
$sectionHeaderClassName = trim((string) ($sectionHeaderClassName ?? ''));
$sectionHeaderAlignment = trim((string) ($sectionHeaderAlignment ?? 'center'));
$sectionHeaderClasses = ['section-header'];

if ($sectionHeaderAlignment === 'left') {
    $sectionHeaderClasses[] = 'section-header--left';
}

if ($sectionHeaderClassName !== '') {
    $sectionHeaderClasses[] = $sectionHeaderClassName;
}
?>

<header class="<?= htmlspecialchars(implode(' ', $sectionHeaderClasses), ENT_QUOTES, 'UTF-8') ?>">
    <h2 class="section-header__title"><?= htmlspecialchars($sectionHeaderTitle, ENT_QUOTES, 'UTF-8') ?></h2>

    <?php if ($sectionHeaderText !== null && $sectionHeaderText !== ''): ?>
        <p class="section-header__text"><?= htmlspecialchars($sectionHeaderText, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
</header>