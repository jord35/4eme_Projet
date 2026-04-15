

    <h1>Mon compte</h1>
    <p>
        <a href="/?action=edit-book">Ajouter ou modifier un livre</a>
        |
        <a href="/?action=books">Voir tous les livres</a>
    </p>

    <section>
        <div>
            <p><strong>Image de profil</strong></p>

            <img
                id="profile-image-preview"
                src="<?= htmlspecialchars((string) ($profilePicture['src'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                <?php if (!empty($profilePicture['srcset'])): ?>
                    srcset="<?= htmlspecialchars((string) $profilePicture['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                <?php if (!empty($profilePicture['sizes'])): ?>
                    sizes="<?= htmlspecialchars((string) $profilePicture['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                alt="Image de profil"
                width="<?= (int) ($profilePicture['width'] ?? 200) ?>"
                height="<?= (int) ($profilePicture['height'] ?? 200) ?>"
                style="max-width: 200px; display: <?= !empty($profilePicture['src']) ? 'block' : 'none' ?>;"
            >

            <p id="no-profile-image" style="display: <?= !empty($profilePicture['src']) ? 'none' : 'block' ?>;">
                Pas d’image
            </p>
        </div>

        <div>
            <p><strong>Pseudo :</strong> <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Membre depuis :</strong> <?= htmlspecialchars($memberSince, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Nombre de livres :</strong> <?= $booksCount ?></p>
        </div>
    </section>

    <hr>

    <form
        id="my-account-form"
        method="POST"
        action="/?action=my-account"
        enctype="multipart/form-data"
    >
        <div>
            <label for="username">Pseudo</label>
            <input
                type="text"
                id="username"
                name="username"
                value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Votre pseudo"
            >
        </div>

        <div>
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Votre email"
            >
        </div>

        <div>
            <label for="password">Nouveau mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Nouveau mot de passe"
            >
        </div>

        <div>
            <label for="profile_image">Image de profil</label>
            <input
                type="file"
                id="profile_image"
                name="profile_image"
                accept="image/*"
            >
        </div>

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <p id="my-account-message"></p>

    <section>
        <h2>Mes livres</h2>

        <?php if (empty($libraryBooks)): ?>
            <p>Vous n'avez pas encore ajouté de livre.</p>
        <?php else: ?>
            <?php foreach ($libraryBooks as $book): ?>
                <article>
                        <p>
                            <a href="/?action=single-book&id=<?= htmlspecialchars((string) $book['id'], ENT_QUOTES, 'UTF-8') ?>">
                                Voir la fiche du livre
                            </a>
                        </p>
                    <?php if (!empty($book['cover']['src'])): ?>
                        <img
                            src="<?= htmlspecialchars((string) $book['cover']['src'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php if (!empty($book['cover']['srcset'])): ?>
                                srcset="<?= htmlspecialchars((string) $book['cover']['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php endif; ?>
                            <?php if (!empty($book['cover']['sizes'])): ?>
                                sizes="<?= htmlspecialchars((string) $book['cover']['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php endif; ?>
                            alt="Couverture de <?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>"
                            width="<?= (int) ($book['cover']['width'] ?? 160) ?>"
                            height="<?= (int) ($book['cover']['height'] ?? 220) ?>"
                            style="max-width: 160px; display: block;"
                        >
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= htmlspecialchars($book['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><?= htmlspecialchars((string) ($book['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                    <p><?= !empty($book['is_available']) ? 'Disponible' : 'Indisponible' ?></p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <script src="/js/common/app.js"></script>
    <script src="/js/my-account.js"></script>
