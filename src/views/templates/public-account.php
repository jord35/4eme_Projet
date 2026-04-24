<?php
$fallbackProfilePicture = '/assets/Icon-mon-compte.svg';
$profileImageSrc = !empty($profilePicture['src'])
    ? (string) $profilePicture['src']
    : $fallbackProfilePicture;

$profileUsername = (string) ($profile['username'] ?? '');
$profileImageAlt = $profileUsername !== ''
    ? 'Image de profil de ' . $profileUsername
    : 'Image de profil utilisateur';

$booksCount = (int) ($profile['books_count'] ?? 0);
$profileId = (int) ($profile['id'] ?? 0);
$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
?>

<?php if ($userNotFound): ?>
    <section class="public-account-page public-account-page--not-found">
        <div class="public-account-page__inner site-frame">
            <h1 class="public-account-page__title">Utilisateur introuvable</h1>
            <p class="public-account-page__empty">Le profil demandé n'existe pas.</p>
        </div>
    </section>
<?php else: ?>
    <section class="public-account-page">
        <div class="public-account-page__inner site-frame">
            <div class="public-account-page__top">
                <div class="public-account-page__panel public-account-page__panel--profile">
                    <div class="public-account-profile-card profile-card">
                        <div class="public-account-profile-media editable-media editable-media--avatar">
                            <img
                                class="public-account-profile-card__image editable-media__preview"
                                id="profile-image-preview"
                                src="<?= htmlspecialchars($profileImageSrc, ENT_QUOTES, 'UTF-8') ?>"
                                <?php if (!empty($profilePicture['srcset']) && $profileImageSrc !== $fallbackProfilePicture): ?>
                                srcset="<?= htmlspecialchars((string) $profilePicture['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                                <?php endif; ?>
                                <?php if (!empty($profilePicture['sizes']) && $profileImageSrc !== $fallbackProfilePicture): ?>
                                sizes="<?= htmlspecialchars((string) $profilePicture['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                                <?php endif; ?>
                                alt="<?= htmlspecialchars($profileImageAlt, ENT_QUOTES, 'UTF-8') ?>"
                                width="<?= (int) ($profilePicture['width'] ?? 200) ?>"
                                height="<?= (int) ($profilePicture['height'] ?? 200) ?>"
                                onerror="this.onerror=null;this.removeAttribute('srcset');this.removeAttribute('sizes');this.src='<?= htmlspecialchars($fallbackProfilePicture, ENT_QUOTES, 'UTF-8') ?>';">
                        </div>

                        <div class="public-account-profile-card__content">
                            <h2 class="public-account-profile-card__name"><?= htmlspecialchars($profileUsername, ENT_QUOTES, 'UTF-8') ?></h2>

                            <p class="public-account-profile-card__meta">
                                Membre depuis
                                <?= htmlspecialchars($memberSince !== '' ? $memberSince : 'peu de temps', ENT_QUOTES, 'UTF-8') ?>
                            </p>

                            <h3 class="public-account-profile-card__library-title">Bibliothèque</h3>

                            <div class="public-account-profile-card__library library-summary">
                                <img
                                    class="public-account-profile-card__library-icon"
                                    src="/assets/icon-book.svg"
                                    alt=""
                                    aria-hidden="true">
                                <p class="public-account-profile-card__library-count">
                                    <?= $booksCount ?>
                                    <?= $booksCount > 1 ? 'livres' : 'livre' ?>
                                </p>
                            </div>

                            <?php if ($profileId !== 0 && $profileId !== $currentUserId): ?>
                                <a
                                    class="message-link button button--outline"
                                    href="/?action=messages&user_id=<?= $profileId ?>">
                                    Ecrire un message
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="public-account-page__books public-account-page__panel public-account-page__panel--books">
                    <?php if (empty($libraryBooks)): ?>
                        <p class="public-account-page__empty">L'utilisateur n'a pas encore ajouté de livre.</p>
                    <?php else: ?>
                        <div class="public-books-table-wrapper library-table-wrapper">
                            <table class="public-books-table library-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Titre</th>
                                        <th scope="col">Auteur</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($libraryBooks as $book): ?>
                                        <?php
                                        $fallbackBookCover = '/assets/img-book-not-found.webp';
                                        $bookCoverSrc = !empty($book['cover']['src'])
                                            ? (string) $book['cover']['src']
                                            : $fallbackBookCover;
                                        $bookCoverAlt = !empty($book['cover']['alt'])
                                            ? (string) $book['cover']['alt']
                                            : 'Couverture non disponible pour ' . (string) $book['title'];
                                        ?>
                                        <tr>
                                            <td>
                                                <img
                                                    class="library-table__cover"
                                                    src="<?= htmlspecialchars($bookCoverSrc, ENT_QUOTES, 'UTF-8') ?>"
                                                    <?php if (!empty($book['cover']['srcset']) && $bookCoverSrc !== $fallbackBookCover): ?>
                                                    srcset="<?= htmlspecialchars((string) $book['cover']['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                                                    <?php endif; ?>
                                                    <?php if (!empty($book['cover']['sizes']) && $bookCoverSrc !== $fallbackBookCover): ?>
                                                    sizes="<?= htmlspecialchars((string) $book['cover']['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                                                    <?php endif; ?>
                                                    alt="<?= htmlspecialchars($bookCoverAlt, ENT_QUOTES, 'UTF-8') ?>"
                                                    width="<?= (int) ($book['cover']['width'] ?? 78) ?>"
                                                    height="<?= (int) ($book['cover']['height'] ?? 78) ?>"
                                                    onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackBookCover, ENT_QUOTES, 'UTF-8') ?>';">
                                            </td>

                                            <td>
                                                <?= htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars((string) $book['author_name'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars((string) mb_strimwidth((string) ($book['description'] ?? ''), 0, 120, '...'), ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>