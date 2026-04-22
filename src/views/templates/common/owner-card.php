<?php
$ownerCardClassName = trim((string) ($ownerCardClassName ?? 'owner-card'));
$ownerCardHref = (string) ($ownerCardHref ?? '#');
$ownerCardName = (string) ($ownerCardName ?? '');
$ownerCardAvatarSrc = (string) ($ownerCardAvatarSrc ?? '/assets/Icon-mon-compte.svg');
$ownerCardAvatarAlt = (string) ($ownerCardAvatarAlt ?? '');
?>

<a
    class="<?= htmlspecialchars($ownerCardClassName, ENT_QUOTES, 'UTF-8') ?>"
    href="<?= htmlspecialchars($ownerCardHref, ENT_QUOTES, 'UTF-8') ?>">
    <span class="owner-card__avatar avatar avatar--sm">
        <img
            src="<?= htmlspecialchars($ownerCardAvatarSrc, ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($ownerCardAvatarAlt, ENT_QUOTES, 'UTF-8') ?>"
            onerror="this.onerror=null;this.src='/assets/Icon-mon-compte.svg';">
    </span>
    <span class="owner-card__name"><?= htmlspecialchars($ownerCardName, ENT_QUOTES, 'UTF-8') ?></span>
</a>