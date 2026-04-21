<section class="book-form-section">
    <a href="/?action=my-account">
        <img src="/assets/icon-back-arrow.svg" alt="" aria-hidden="true">
        Retour
    </a>
    <h1><?= $book->getId() > 0 ? 'Modifier les informations' : 'Entrez les informations' ?></h1>
    <div
        id="book-form-message"
        class="form-message"
        data-state=""
        role="status"
        aria-live="polite"
        aria-atomic="true"
        hidden></div>

    <form
        id="edit-book-form"
        action="/?action=edit-book"
        method="post"
        enctype="multipart/form-data"
        class="book-form">
        <?php if ($book->getId() > 0): ?>
            <input
                type="hidden"
                name="id"
                value="<?= htmlspecialchars((string) $book->getId(), ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>

        <div class="book-form-layout">
            <div class="book-form-media-card">
                <div class="book-form-media">
                    <p>Photo</p>
                    <?php if (!empty($coverPicture)): ?>
                        <img
                            id="picture-preview"
                            src="<?= htmlspecialchars($coverPicture['src'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php if (!empty($coverPicture['srcset'])): ?>
                            srcset="<?= htmlspecialchars($coverPicture['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php endif; ?>
                            sizes="220px"
                            alt="<?= htmlspecialchars($coverPicture['alt'] ?? 'Aperçu de la couverture', ENT_QUOTES, 'UTF-8') ?>"
                            width="<?= (int) ($coverPicture['width'] ?? 220) ?>"
                            height="<?= (int) ($coverPicture['height'] ?? 320) ?>"
                            onerror="this.onerror=null;this.removeAttribute('srcset');this.src='/assets/img-book-not-found.webp';">
                    <?php else: ?>
                        <img
                            id="picture-preview"
                            src="/assets/img-book-not-found.webp"
                            alt="Aperçu de la couverture"
                            width="220"
                            height="320">
                    <?php endif; ?>

                    <div class="book-form-media-edit-wrapper">
                        <label class="book-form-media-edit" for="picture">Modifier la photo</label>
                        <input
                            class="visually-hidden"
                            type="file"
                            id="picture"
                            name="picture"
                            accept="image/png, image/jpeg, image/webp">
                    </div>
                </div>


            </div>

            <div class="book-form-infos">
                <div class="book-form-field">
                    <label for="title">Titre</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="book-form-field">
                    <label for="author_name">Auteur</label>
                    <input
                        type="text"
                        id="author_name"
                        name="author_name"
                        value="<?= htmlspecialchars($book->getAuthorName(), ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>

                <div class="book-form-field">
                    <label for="description">Commentaire</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="8"
                        placeholder="Écris un commentaire détaillé..."><?= htmlspecialchars((string) ($book->getDescription() ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <fieldset class="book-form-field book-form-fieldset">
                    <legend>Disponibilité</legend>

                    <label for="available-true">
                        <input
                            type="radio"
                            id="available-true"
                            name="is_available"
                            value="1"
                            <?= $book->getIsAvailable() ? 'checked' : '' ?>>
                        Disponible
                    </label>

                    <label for="available-false">
                        <input
                            type="radio"
                            id="available-false"
                            name="is_available"
                            value="0"
                            <?= !$book->getIsAvailable() ? 'checked' : '' ?>>
                        Non disponible
                    </label>
                </fieldset>

                <button type="submit">
                    Valider
                </button>
            </div>
        </div>
    </form>
</section>

<script src="/js/editbook.js" defer></script>