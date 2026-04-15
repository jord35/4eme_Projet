

    <section>
        <p><a href="/?action=books">Retour aux livres</a></p>

        <h1><?= htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p><strong>Auteur :</strong> <?= htmlspecialchars((string) $book['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
        <p>
            <strong>Proposé par :</strong>
            <a href="/?action=public-account&username=<?= htmlspecialchars((string) $book['owner_username'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars((string) $book['owner_username'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        </p>
        <p><strong>Disponibilité :</strong> <?= !empty($book['is_available']) ? 'Disponible' : 'Indisponible' ?></p>

        <?php if (!empty($coverPicture)): ?>
            <img
                src="<?= htmlspecialchars((string) $coverPicture['src'], ENT_QUOTES, 'UTF-8') ?>"
                <?php if (!empty($coverPicture['srcset'])): ?>
                    srcset="<?= htmlspecialchars((string) $coverPicture['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                <?php if (!empty($coverPicture['sizes'])): ?>
                    sizes="<?= htmlspecialchars((string) $coverPicture['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                <?php endif; ?>
                alt="<?= htmlspecialchars((string) ($coverPicture['alt'] ?? ('Couverture de ' . $book['title'])), ENT_QUOTES, 'UTF-8') ?>"
                width="<?= (int) ($coverPicture['width'] ?? 375) ?>"
                height="<?= (int) ($coverPicture['height'] ?? 520) ?>"
                style="max-width: 375px; display: block;"
            >
        <?php else: ?>
            <p>Pas d'image disponible.</p>
        <?php endif; ?>

        <div>
            <h2>Description</h2>
            <p><?= nl2br(htmlspecialchars((string) ($book['description'] ?? ''), ENT_QUOTES, 'UTF-8')) ?></p>
        </div>
    </section>

