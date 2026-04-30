<?php
$bookCardData = isset($bookCardData) && is_array($bookCardData) ? $bookCardData : [];
$bookCardId = (int) ($bookCardData['id'] ?? 0);
$bookCardTitle = (string) ($bookCardData['title'] ?? '');
$bookCardAuthor = (string) ($bookCardData['author_name'] ?? '');
$bookCardOwnerName = (string) ($bookCardData['owner']['username'] ?? '');
$bookCardOwnerLabelPrefix = (string) ($bookCardOwnerLabelPrefix ?? 'Proposé par');
$bookCardHref = (string) ($bookCardHref ?? ('/?action=single-book&id=' . $bookCardId));
$bookCardCover = is_array($bookCardData['cover'] ?? null) ? $bookCardData['cover'] : null;
$bookCardFallbackText = (string) ($bookCardFallbackText ?? "Pas d'image");
$bookCardStatusLabel = (string) ($bookCardStatusLabel ?? 'Livre indisponible');
$bookCardShowStatusText = isset($bookCardShowStatusText) ? (bool) $bookCardShowStatusText : false;
$bookCardUnavailableText = (string) ($bookCardUnavailableText ?? 'non dispo.');
?>

<article class="book-card">
    <a
        class="book-card__link"
        href="<?= htmlspecialchars($bookCardHref, ENT_QUOTES, 'UTF-8') ?>">

        <div class="book-card__cover">
            <?php if (!empty($bookCardCover)): ?>
                <img
                    class="book-card__image"
                    src="<?= htmlspecialchars((string) $bookCardCover['src'], ENT_QUOTES, 'UTF-8') ?>"
                    <?php if (!empty($bookCardCover['srcset'])): ?>
                    srcset="<?= htmlspecialchars((string) $bookCardCover['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                    <?php endif; ?>
                    <?php if (!empty($bookCardCover['sizes'])): ?>
                    sizes="<?= htmlspecialchars((string) $bookCardCover['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                    <?php endif; ?>
                    alt="<?= htmlspecialchars((string) ($bookCardCover['alt'] ?? ('Couverture de ' . $bookCardTitle)), ENT_QUOTES, 'UTF-8') ?>"
                    width="<?= (int) ($bookCardCover['width'] ?? 240) ?>"
                    height="<?= (int) ($bookCardCover['height'] ?? 320) ?>">
            <?php else: ?>
                <div class="book-card__placeholder">
                    <span><?= htmlspecialchars($bookCardFallbackText, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endif; ?>

            <?php if (empty($bookCardData['is_available'])): ?>
                <?php if ($bookCardShowStatusText): ?>
                    <p class="book-card__status">
                        <?= htmlspecialchars($bookCardUnavailableText, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php else: ?>
                    <span class="book-card__status" aria-label="<?= htmlspecialchars($bookCardStatusLabel, ENT_QUOTES, 'UTF-8') ?>"></span>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="book-card__body">
            <h2 class="book-card__title"><?= htmlspecialchars($bookCardTitle, ENT_QUOTES, 'UTF-8') ?></h2>
            <p class="book-card__author"><?= htmlspecialchars($bookCardAuthor, ENT_QUOTES, 'UTF-8') ?></p>
            <p class="book-card__owner">
                <?= htmlspecialchars($bookCardOwnerLabelPrefix, ENT_QUOTES, 'UTF-8') ?>
                <?= htmlspecialchars($bookCardOwnerName, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
    </a>
</article>

<?php
unset(
    $bookCardData,
    $bookCardId,
    $bookCardTitle,
    $bookCardAuthor,
    $bookCardOwnerName,
    $bookCardOwnerLabelPrefix,
    $bookCardHref,
    $bookCardCover,
    $bookCardFallbackText,
    $bookCardStatusLabel,
    $bookCardShowStatusText,
    $bookCardUnavailableText
);
?>