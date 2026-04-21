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

<section class="single-book-section">


    <div class="single-book-layout">
        <div class="single-book-media">
            <img
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

        <div class="single-book-content">
            <div class="single-book-intro">
                <h1><?= htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p>par <?= htmlspecialchars((string) $book['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>



            <div class="single-book-description">
                <h2>Description</h2>
                <p><?= nl2br(htmlspecialchars((string) ($book['description'] ?? ''), ENT_QUOTES, 'UTF-8')) ?></p>
            </div>

            <div class="single-book-owner">
                <h2>Propriétaire</h2>

                <div class="user-card">
                    <img
                        src="<?= htmlspecialchars($ownerAvatar, ENT_QUOTES, 'UTF-8') ?>"
                        alt="Photo de profil de <?= htmlspecialchars($ownerUsername, ENT_QUOTES, 'UTF-8') ?>"
                        onerror="this.onerror=null;this.src='/assets/Icon-mon-compte.svg';">
                    <p><?= htmlspecialchars($ownerUsername, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>

            <div class="single-book-actions">
                <a
                    class="single-book-message-link"
                    href="/?action=messages&username=<?= urlencode($ownerUsername) ?>">
                    Envoyer un message
                </a>
            </div>
        </div>
    </div>
</section>