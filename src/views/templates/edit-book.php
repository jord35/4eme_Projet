<section class="book-edit-page">
    <div class="book-edit-page__inner site-frame">
        <a class="book-edit-page__back-link" href="/?action=my-account">
            <img
                class="book-edit-page__back-icon"
                src="/assets/icon-back-arrow.svg"
                alt=""
                aria-hidden="true">
            <span class="book-edit-page__back-text">Retour</span>
        </a>

        <h1 class="book-edit-page__title">
            <?= $book->getId() > 0 ? 'Modifier les informations' : 'Entrez les informations' ?>
        </h1>

        <div
            id="book-form-message"
            class="book-edit-page__message form-message"
            data-state=""
            role="status"
            aria-live="polite"
            aria-atomic="true"
            hidden></div>

        <form
            id="edit-book-form"
            class="book-edit-form form-shell"
            action="/?action=edit-book"
            method="post"
            enctype="multipart/form-data">

            <?php if ($book->getId() > 0): ?>
                <input
                    type="hidden"
                    name="id"
                    value="<?= htmlspecialchars((string) $book->getId(), ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>

            <div class="book-edit-form__panel form-panel">
                <div class="book-edit-form__layout form-layout">
                    <div class="book-edit-form__media-column">
                        <div class="book-edit-form__media-block editable-media">
                            <p class="book-edit-form__media-label">Photo</p>

                            <div class="book-edit-form__media">
                                <?php if (!empty($coverPicture)): ?>
                                    <img
                                        class="book-edit-form__image editable-media__preview"
                                        id="picture-preview"
                                        src="<?= htmlspecialchars($coverPicture['src'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?php if (!empty($coverPicture['srcset'])): ?>
                                        srcset="<?= htmlspecialchars($coverPicture['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?php endif; ?>
                                        <?php if (!empty($coverPicture['sizes'])): ?>
                                        sizes="<?= htmlspecialchars((string) $coverPicture['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?php endif; ?>
                                        alt="<?= htmlspecialchars($coverPicture['alt'] ?? 'Aperçu de la couverture', ENT_QUOTES, 'UTF-8') ?>"
                                        width="<?= (int) ($coverPicture['width'] ?? 220) ?>"
                                        height="<?= (int) ($coverPicture['height'] ?? 320) ?>"
                                        onerror="this.onerror=null;this.removeAttribute('srcset');this.src='/assets/img-book-not-found.webp';">
                                <?php else: ?>
                                    <img
                                        class="book-edit-form__image editable-media__preview"
                                        id="picture-preview"
                                        src="/assets/img-book-not-found.webp"
                                        alt="Aperçu de la couverture"
                                        width="220"
                                        height="320">
                                <?php endif; ?>
                            </div>

                            <div class="book-edit-form__media-action editable-media__edit">
                                <label class="book-edit-form__media-link" for="picture">Modifier la photo</label>
                                <input
                                    class="visually-hidden"
                                    type="file"
                                    id="picture"
                                    name="picture"
                                    accept="image/png, image/jpeg, image/webp">
                            </div>
                        </div>
                    </div>

                    <div class="book-edit-form__fields-column">
                        <div class="book-edit-form__field form-field">
                            <label class="book-edit-form__label" for="title">Titre</label>
                            <input
                                class="book-edit-form__input"
                                type="text"
                                id="title"
                                name="title"
                                value="<?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?>"
                                required>
                        </div>

                        <div class="book-edit-form__field form-field">
                            <label class="book-edit-form__label" for="author_name">Auteur</label>
                            <input
                                class="book-edit-form__input"
                                type="text"
                                id="author_name"
                                name="author_name"
                                value="<?= htmlspecialchars($book->getAuthorName(), ENT_QUOTES, 'UTF-8') ?>"
                                required>
                        </div>

                        <div class="book-edit-form__field form-field">
                            <label class="book-edit-form__label" for="description">Commentaire</label>
                            <textarea
                                class="book-edit-form__textarea"
                                id="description"
                                name="description"
                                rows="8"
                                placeholder="Écris un commentaire détaillé..."><?= htmlspecialchars((string) ($book->getDescription() ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="book-edit-form__field book-edit-form__field--availability form-field">
                            <label class="book-edit-form__label" for="is_available">Disponibilité</label>
                            <div class="book-edit-form__select-wrap">
                                <select
                                    class="book-edit-form__select"
                                    id="is_available"
                                    name="is_available">
                                    <option value="1" <?= $book->getIsAvailable() ? 'selected' : '' ?>>Disponible</option>
                                    <option value="0" <?= !$book->getIsAvailable() ? 'selected' : '' ?>>Non disponible</option>
                                </select>
                            </div>
                        </div>

                        <button class="book-edit-form__submit button button--primary" type="submit">
                            Valider
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script src="/js/editbook.js" defer></script>