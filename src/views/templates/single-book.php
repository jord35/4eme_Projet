<?php
$fallbackCover = '/assets/img-book-not-found.webp';

$coverSrc = !empty($coverPicture['src'])
    ? (string) $coverPicture['src']
    : $fallbackCover;

$coverAlt = !empty($coverPicture['alt'])
    ? (string) $coverPicture['alt']
    : 'Image de couverture non disponible pour ' . (string) $book['title'];

$ownerUsername = (string) ($book['owner_username'] ?? '');
$ownerAvatar = !empty($book['owner_avatar'])
    ? (string) $book['owner_avatar']
    : '/assets/Icon-mon-compte.svg';
?>

<section class="book-detail">
    <p class="book-detail__breadcrumb">Nos livres > <?= $book['title'] ?> </p>
    <div class="book-detail__inner">
        <div class="book-detail__media">
            <img
                class="book-detail__image"
                src="<?= htmlspecialchars($coverSrc, ENT_QUOTES, 'UTF-8') ?>"
                <?php if (!empty($coverPicture['srcset']) && $coverSrc !== $fallbackCover): ?>
                srcset="<?= htmlspecialchars((string) $coverPicture['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                <?php if (!empty($coverPicture['sizes']) && $coverSrc !== $fallbackCover): ?>
                sizes="<?= htmlspecialchars((string) $coverPicture['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                alt="<?= htmlspecialchars($coverAlt, ENT_QUOTES, 'UTF-8') ?>"
                width="<?= (int) ($coverPicture['width'] ?? 375) ?>"
                height="<?= (int) ($coverPicture['height'] ?? 520) ?>"
                onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackCover, ENT_QUOTES, 'UTF-8') ?>';">
        </div>

        <div class="book-detail__content">
            <div class="book-detail__intro">
                <h1 class="book-detail__title"><?= htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="book-detail__author">par <?= htmlspecialchars((string) $book['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>

            <div class="book-detail__description">
                <h2 class="book-detail__section-title">Description</h2>
                <div class="book-detail__description-text">
                    <p><?= nl2br(htmlspecialchars((string) ($book['description'] ?? ''), ENT_QUOTES, 'UTF-8')) ?></p>
                </div>
            </div>

            <div class="book-detail__owner">
                <h2 class="book-detail__section-title">Propriétaire</h2>

                <?php
                $ownerCardClassName = 'book-detail__owner-card owner-card';
                $ownerCardHref = '/?action=public-account&username=' . urlencode($ownerUsername);
                $ownerCardName = $ownerUsername;
                $ownerCardAvatarSrc = $ownerAvatar;
                $ownerCardAvatarAlt = 'Photo de profil de ' . $ownerUsername;
                require __DIR__ . '/common/owner-card.php';
                ?>
            </div>

            <div class="book-detail__actions">
                <a
                    class="book-detail__message-link button button--primary"
                    href="/?action=messages&username=<?= urlencode($ownerUsername) ?>">
                    Envoyer un message
                </a>
            </div>
        </div>
    </div>
</section>