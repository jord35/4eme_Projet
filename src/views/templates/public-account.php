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
    <section class="public-account-section">
        <h1>Utilisateur introuvable</h1>
        <p>Le profil demandé n'existe pas.</p>
    </section>
<?php else: ?>
    <section class="public-account-section">


        <div class="public-account-layout">
            <div>
                <div class="public-account-profile-card">
                    <div class="public-account-profile-media">
                        <img
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

                    <h2><?= htmlspecialchars($profileUsername, ENT_QUOTES, 'UTF-8') ?></h2>

                    <p>
                        Membre depuis
                        <?= htmlspecialchars($memberSince !== '' ? $memberSince : 'peu de temps', ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <h3>Bibliothèque</h3>

                    <div class="library-summary">
                        <img src="/assets/icon-book.svg" alt="" aria-hidden="true">
                        <p>
                            <?= $booksCount ?>
                            <?= $booksCount > 1 ? 'livres' : 'livre' ?>
                        </p>
                    </div>

                    <?php if ($profileId !== 0 && $profileId !== $currentUserId): ?>
                        <a
                            class="message-link"
                            href="/?action=messages&user_id=<?= $profileId ?>">
                            Envoyer un message
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="public-account-books">

                <?php if (empty($libraryBooks)): ?>
                    <p>L'utilisateur n'a pas encore ajouté de livre.</p>
                <?php else: ?>
                    <div class="public-books-table-wrapper">
                        <table class="public-books-table">
                            <thead>
                                <tr>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col">Auteur</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Disponibilité</th>
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
                                                src="<?= htmlspecialchars($bookCoverSrc, ENT_QUOTES, 'UTF-8') ?>"
                                                alt="<?= htmlspecialchars($bookCoverAlt, ENT_QUOTES, 'UTF-8') ?>"
                                                width="78"
                                                height="78"
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

                                        <td>
                                            <?php if (!empty($book['is_available'])): ?>
                                                <span class="status status--available">Disponible</span>
                                            <?php else: ?>
                                                <span class="status status--unavailable">Non dispo.</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>