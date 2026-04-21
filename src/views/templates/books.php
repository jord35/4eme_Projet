<section class="books-section">

    <div class="books-content">
        <h1>Nos livres à l’échange</h1>



        <form class="books-search-form" action="" method="get">
            <input type="hidden" name="action" value="books">

            <label for="books-search" class="visually-hidden">Rechercher un livre</label>

            <div class="search-field">
                <img src="/assets/icon-search.svg" alt="" aria-hidden="true">
                <input
                    type="search"
                    id="books-search"
                    name="search"
                    placeholder="Rechercher un livre"
                    value="<?= htmlspecialchars((string) ($_GET['search'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </form>
    </div>


    <?php if (empty($bookCards)): ?>
        <p>Aucun livre n'est disponible pour le moment.</p>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($bookCards as $bookCard): ?>
                <article class="book-card">
                    <a
                        class="book-card-link"
                        href="/?action=single-book&id=<?= htmlspecialchars((string) $bookCard['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <div class="book-card-cover">
                            <?php if (!empty($bookCard['cover'])): ?>
                                <img
                                    src="<?= htmlspecialchars((string) $bookCard['cover']['src'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?php if (!empty($bookCard['cover']['srcset'])): ?>
                                    srcset="<?= htmlspecialchars((string) $bookCard['cover']['srcset'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?php endif; ?>
                                    <?php if (!empty($bookCard['cover']['sizes'])): ?>
                                    sizes="<?= htmlspecialchars((string) $bookCard['cover']['sizes'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?php endif; ?>
                                    alt="<?= htmlspecialchars((string) ($bookCard['cover']['alt'] ?? ('Couverture de ' . $bookCard['title'])), ENT_QUOTES, 'UTF-8') ?>"
                                    width="<?= (int) ($bookCard['cover']['width'] ?? 240) ?>"
                                    height="<?= (int) ($bookCard['cover']['height'] ?? 320) ?>">
                            <?php else: ?>
                                <div class="book-card-cover-placeholder">
                                    <span>Pas d'image</span>
                                </div>
                            <?php endif; ?>

                            <?php if (!$bookCard['is_available']): ?>
                                <div>
                                    <p class="book-card-status" aria-label="Livre indisponible">non dispo.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="book-card-body">
                            <h2><?= htmlspecialchars((string) $bookCard['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p><?= htmlspecialchars((string) $bookCard['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p>
                                Vendu par:
                                <?= htmlspecialchars((string) $bookCard['owner']['username'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>