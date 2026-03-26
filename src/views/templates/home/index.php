<section class="home">
    <h1>Bienvenue</h1>
    <p>Découvrez les derniers livres ajoutés.</p>

    <div id="book-list">
        <?php if (!empty($books)) : ?>
            <?php foreach ($books as $book) : ?>
                <article class="book-card" data-book-id="<?= $book->getId(); ?>">
                    <div class="book-card__image">
                        <img
                            src="<?= htmlspecialchars($book->getImagePath()); ?>"
                            alt="<?= htmlspecialchars($book->getTitle()); ?>"
                        >
                    </div>

                    <div class="book-card__content">
                        <h2><?= htmlspecialchars($book->getTitle()); ?></h2>
                        <p><?= htmlspecialchars($book->getAuthorName()); ?></p>
                        <p>Proposé par <?= htmlspecialchars($book->getUsername()); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <p id="no-books-message">Aucun livre disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

<script src="/Js/app.js"></script>
<script src="/Js/home/index.js"></script>
