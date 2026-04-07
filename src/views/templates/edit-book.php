<section class="book-form-section">
    <div class="book-form-header">
        <div>
            <h1><?= $book->getId() > 0 ? 'Modifier un livre' : 'Créer un livre' ?></h1>
        </div>

        <?php if ($book->getId() > 0): ?>
            <a class="button-secondary" href="/?action=edit-book">
                Créer un nouveau livre
            </a>
        <?php endif; ?>
    </div>

    <div
        id="book-form-message"
        class="form-message"
        data-state=""
        hidden
    ></div>

    <form
        id="edit-book-form"
        action="/?action=edit-book"
        method="post"
        enctype="multipart/form-data"
        class="book-form"
    >
        <?php if ($book->getId() > 0): ?>
            <input
                type="hidden"
                name="id"
                value="<?= htmlspecialchars((string) $book->getId(), ENT_QUOTES, 'UTF-8') ?>"
            >
        <?php endif; ?>

        <div class="form-group">
            <label for="picture">Image</label>
            <input
                type="file"
                id="picture"
                name="picture"
                accept="image/png, image/jpeg, image/webp"
            >
        </div>

        <div class="form-group">
            <p>Aperçu</p>

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
                    style="display: block; max-width: 220px; border-radius: 8px;"
                >
            <?php else: ?>
                <img
                    id="picture-preview"
                    src=""
                    alt="Aperçu de l'image sélectionnée"
                    width="220"
                    height="320"
                    style="display: none; max-width: 220px; border-radius: 8px;"
                >
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="title">Titre</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?= htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="author_name">Auteur</label>
            <input
                type="text"
                id="author_name"
                name="author_name"
                value="<?= htmlspecialchars($book->getAuthorName(), ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="description">Commentaire</label>
            <textarea
                id="description"
                name="description"
                rows="8"
                placeholder="Écris un commentaire détaillé..."
            ><?= htmlspecialchars((string) ($book->getDescription() ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <fieldset class="form-group">
            <legend>Disponibilité</legend>

            <label for="available-true">
                <input
                    type="radio"
                    id="available-true"
                    name="is_available"
                    value="1"
                    <?= $book->getIsAvailable() ? 'checked' : '' ?>
                >
                Disponible
            </label>

            <label for="available-false">
                <input
                    type="radio"
                    id="available-false"
                    name="is_available"
                    value="0"
                    <?= !$book->getIsAvailable() ? 'checked' : '' ?>
                >
                Non disponible
            </label>
        </fieldset>

        <button type="submit">
            <?= $book->getId() > 0 ? 'Mettre à jour' : 'Créer le livre' ?>
        </button>
    </form>
</section>

<script src="/js/editbook.js" defer></script>