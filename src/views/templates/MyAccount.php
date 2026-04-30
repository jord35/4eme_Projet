<?php
/**
 * Variables injected by View::render() via extract($params).
 *
 * @var array<string, mixed> $profile
 * @var array<string, mixed>|null $profilePicture
 * @var array<int, array<string, mixed>> $libraryBooks
 * @var string $username
 * @var string $email
 * @var string $createdAt
 * @var int $booksCount
 * @var string $memberSince
 */
$fallbackProfilePicture = '/assets/Icon-mon-compte.svg';
$profileImageSrc = !empty($profilePicture['src'])
    ? (string) $profilePicture['src']
    : $fallbackProfilePicture;

$profileImageAlt = $username !== ''
    ? 'Image de profil de ' . $username
    : 'Image de profil utilisateur';
?>

<section class="account-page">
    <div class="account-page__inner site-frame">
        <h1 class="account-page__title">Mon compte</h1>

        <p
            id="my-account-message"
            class="account-page__message form-message"
            data-state=""
            role="status"
            aria-live="polite"
            aria-atomic="true"
            hidden></p>

        <form
            class="account-page__top"
            id="my-account-form"
            method="post"
            action="/?action=my-account"
            enctype="multipart/form-data"
            novalidate>
            <div class="account-page__panel account-page__panel--profile">
                <div class="account-profile-card profile-card">
                    <div class="account-profile-card__media editable-media editable-media--avatar">
                        <img
                            class="account-profile-card__image editable-media__preview"
                            id="profile-image-preview"
                            data-fallback-src="<?= htmlspecialchars($fallbackProfilePicture, ENT_QUOTES, 'UTF-8') ?>"
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

                        <div class="account-profile-card__edit">
                            <label class="account-profile-card__edit-button editable-media__edit link-action link-action--muted" for="profile_image">modifier</label>
                            <input
                                class="visually-hidden"
                                type="file"
                                id="profile_image"
                                name="profile_image"
                                accept="image/*">
                        </div>
                    </div>

                    <div class="account-profile-card__content">
                        <h2 class="account-profile-card__name" id="my-account-profile-name"><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></h2>

                        <p class="account-profile-card__meta">
                            Membre depuis
                            <?= htmlspecialchars($memberSince !== '' ? $memberSince : 'peu de temps', ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <h3 class="account-profile-card__library-title">Bibliothèque</h3>

                        <div class="account-profile-card__library">
                            <img
                                class="account-profile-card__library-icon"
                                src="/assets/icon-book.svg"
                                alt=""
                                aria-hidden="true">
                            <p class="account-profile-card__library-count">
                                <?= (int) $booksCount ?>
                                <?= (int) $booksCount > 1 ? 'livres' : 'livre' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="account-page__panel account-page__panel--details">
                <div class="account-details-form form-shell">

                    <h2 class="account-details-form__title">Vos informations personnelles</h2>

                    <div class="account-details-form__field form-field">
                        <label class="account-details-form__label" for="email">Adresse email</label>
                        <input
                            class="account-details-form__input"
                            type="email"
                            id="email"
                            name="email">
                    </div>

                    <div class="account-details-form__field form-field">
                        <label class="account-details-form__label" for="password">Mot de passe</label>
                        <input
                            class="account-details-form__input"
                            type="password"
                            id="password"
                            name="password">
                    </div>

                    <div class="account-details-form__field form-field">
                        <label class="account-details-form__label" for="username">Pseudo</label>
                        <input
                            class="account-details-form__input"
                            type="text"
                            id="username"
                            name="username">
                    </div>

                    <button class="account-details-form__submit button button--outline" type="submit">Enregistrer</button>
                </div>
            </div>
        </form>

        <div class="account-library">
            <?php if (empty($libraryBooks)): ?>
                <p class="account-library__empty">Vous n'avez pas encore ajouté de livre.</p>
            <?php else: ?>
                <div class="account-library__table-wrapper library-table-wrapper">
                    <table class="account-library__table library-table">
                        <thead class="account-library__head">
                            <tr class="account-library__row account-library__row--head">
                                <th class="account-library__cell account-library__cell--head account-library__cell--cover" scope="col">Photo</th>
                                <th class="account-library__cell account-library__cell--head account-library__cell--title" scope="col">Titre</th>
                                <th class="account-library__cell account-library__cell--head account-library__cell--author" scope="col">Auteur</th>
                                <th class="account-library__cell account-library__cell--head account-library__cell--description" scope="col">Description</th>
                                <th class="account-library__cell account-library__cell--head account-library__cell--status" scope="col">Disponibilité</th>
                                <th class="account-library__cell account-library__cell--head account-library__cell--actions" scope="col">Action</th>
                            </tr>
                        </thead>

                        <tbody class="account-library__body">
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
                                <tr class="account-library__row">
                                    <td class="account-library__cell account-library__cell--cover">
                                        <img
                                            class="account-library__cover library-table__cover"
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

                                    <td class="account-library__cell account-library__cell--title">
                                        <?= htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td class="account-library__cell account-library__cell--author">
                                        <?= htmlspecialchars((string) $book['author_name'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td class="account-library__cell account-library__cell--description">
                                        <?= htmlspecialchars((string) mb_strimwidth((string) ($book['description'] ?? ''), 0, 85, '...'), ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td class="account-library__cell account-library__cell--status">
                                        <?php if (!empty($book['is_available'])): ?>
                                            <span class="status status--available">Disponible</span>
                                        <?php else: ?>
                                            <span class="status status--unavailable">Non dispo.</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="account-library__cell account-library__cell--actions">
                                        <div class="account-library__actions book-actions">
                                            <a
                                                class="account-library__action-link link-action"
                                                href="/?action=edit-book&id=<?= (int) $book['id'] ?>">
                                                Éditer
                                            </a>

                                            <form
                                                method="post"
                                                action="/?action=my-account"
                                                onsubmit="return confirm('Supprimer ce livre ?');">
                                                <input type="hidden" name="form_action" value="delete_book">
                                                <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                                                <button
                                                    class="account-library__action-link account-library__action-link--danger link-action link-action--destructive"
                                                    type="submit">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <script src="/js/my-account.js" defer></script>
    </div>
</section>