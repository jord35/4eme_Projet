<?php
$profileCardClassName = trim((string) ($profileCardClassName ?? 'profile-card'));
$profileCardTitle = (string) ($profileCardTitle ?? '');
$profileCardMeta = (string) ($profileCardMeta ?? '');
$profileCardSummaryHtml = (string) ($profileCardSummaryHtml ?? '');
$profileCardActionsHtml = (string) ($profileCardActionsHtml ?? '');
$profileCardMediaHtml = (string) ($profileCardMediaHtml ?? '');
?>

<article class="<?= htmlspecialchars($profileCardClassName, ENT_QUOTES, 'UTF-8') ?>">
    <?= $profileCardMediaHtml ?>

    <?php if ($profileCardTitle !== ''): ?>
        <h2 class="profile-card__title"><?= htmlspecialchars($profileCardTitle, ENT_QUOTES, 'UTF-8') ?></h2>
    <?php endif; ?>

    <?php if ($profileCardMeta !== ''): ?>
        <p class="profile-card__meta"><?= htmlspecialchars($profileCardMeta, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?= $profileCardSummaryHtml ?>
    <?= $profileCardActionsHtml ?>
</article>