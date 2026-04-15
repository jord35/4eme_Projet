<p><a href="/?action=books">Retour aux livres</a></p>

<?php if ($userNotFound): ?>
    <h1>Utilisateur introuvable</h1>
    <p>Le profil demandé n'existe pas.</p>
<?php else: ?>
    <h1>Compte public</h1>

    <section class="public-account-profile">
        <div>
            <p><strong>Image de profil</strong></p>

            <img
                src="<?= htmlspecialchars((string) ($profilePicture['src'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                <?php if (!empty($profilePicture['srcset'])): ?>
                    srcset="<?= htmlspecialchars((string) $profilePicture['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                <?php if (!empty($profilePicture['sizes'])): ?>
                    sizes="<?= htmlspecialchars((string) $profilePicture['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                alt="Image de profil de <?= htmlspecialchars((string) $profile['username'], ENT_QUOTES, 'UTF-8') ?>"
                width="<?= (int) ($profilePicture['width'] ?? 200) ?>"
                height="<?= (int) ($profilePicture['height'] ?? 200) ?>"
                style="max-width: 200px; display: <?= !empty($profilePicture['src']) ? 'block' : 'none' ?>;"
            >

            <?php if (empty($profilePicture['src'])): ?>
                <p>Pas d’image</p>
            <?php endif; ?>
        </div>

        <div>
            <p><strong>Pseudo :</strong> <?= htmlspecialchars((string) $profile['username'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Membre depuis :</strong> <?= htmlspecialchars($memberSince, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Nombre de livres :</strong> <?= (int) ($profile['books_count'] ?? 0) ?></p>

            <?php if ((int) ($profile['id'] ?? 0) !== (int) ($_SESSION['user_id'] ?? 0)): ?>
            <p>
                <a class="btn" href="/?action=messages&user_id=<?= (int) ($profile['id'] ?? 0) ?>">
                    Écrire un message
                </a>
            </p>
<?php endif; ?>
        </div>
    </section>

    <hr>

    <section>
        <h2>Livres de <?= htmlspecialchars((string) $profile['username'], ENT_QUOTES, 'UTF-8') ?></h2>

        <?php if (empty($libraryBooks)): ?>
            <p>Cet utilisateur n'a pas encore ajouté de livre.</p>
        <?php else: ?>
            <?php foreach ($libraryBooks as $book): ?>
                <article>
                    <?php if (!empty($book['cover']['src'])): ?>
                        <img
                            src="<?= htmlspecialchars((string) $book['cover']['src'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php if (!empty($book['cover']['srcset'])): ?>
                                srcset="<?= htmlspecialchars((string) $book['cover']['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php endif; ?>
                            <?php if (!empty($book['cover']['sizes'])): ?>
                                sizes="<?= htmlspecialchars((string) $book['cover']['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php endif; ?>
                            alt="Couverture de <?= htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8') ?>"
                            width="<?= (int) ($book['cover']['width'] ?? 160) ?>"
                            height="<?= (int) ($book['cover']['height'] ?? 220) ?>"
                            style="max-width: 160px; display: block;"
                        >
                    <?php endif; ?>

                    <h3><?= htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= htmlspecialchars((string) $book['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><?= htmlspecialchars((string) ($book['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                    <p><?= !empty($book['is_available']) ? 'Disponible' : 'Indisponible' ?></p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
<?php endif; ?>