<section class="home-hero">
    <div class="home-hero-content">
        <h1>Rejoignez nos lecteurs passionnés</h1>

        <p>
            Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture.
            Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
        </p>

        <a href="/?action=books">Découvrir</a>
    </div>

    <img
        src="/assets/hamza-nouasria-desktop.webp"
        alt="Personne dans une librairie entourée de livres">
</section>

<section class="home-latest-books">
    <header class="home-section-header">
        <h2>Les derniers livres ajoutés</h2>
        <p>Découvrez les dernières nouveautés proposées par la communauté.</p>
    </header>

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
                                <span class="book-card-status" aria-label="Livre indisponible"></span>
                            <?php endif; ?>
                        </div>

                        <div class="book-card-body">
                            <h3><?= htmlspecialchars((string) $bookCard['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><?= htmlspecialchars((string) $bookCard['author_name'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p>
                                Proposé par
                                <?= htmlspecialchars((string) $bookCard['owner']['username'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <p>
        <a href="/?action=books">Voir tous les livres</a>
    </p>
</section>

<section class="home-how-it-works">
    <header class="home-section-header">
        <h2>Comment ça marche ?</h2>
        <p>
            Échanger des livres avec Tom Troc, c’est simple et amusant.
            Suivez ces étapes pour commencer.
        </p>
    </header>

    <div class="steps-list">
        <article class="step-card">
            <p>Inscrivez-vous gratuitement sur notre plateforme.</p>
        </article>

        <article class="step-card">
            <p>Ajoutez les livres que vous souhaitez échanger à votre profil.</p>
        </article>

        <article class="step-card">
            <p>Parcourez les livres disponibles chez d'autres membres.</p>
        </article>

        <article class="step-card">
            <p>Proposez un échange et discutez avec d'autres passionnés de lecture.</p>
        </article>
    </div>

    <p>
        <a href="/?action=books">Voir tous les livres</a>
    </p>
</section>

<section class="home-values">
    <div class="home-values-media">
        <img
            src="/assets/Mask-group-desktop.webp"
            alt="">
    </div>

    <div class="home-values-content">
        <header class="home-section-header">
            <h2>Nos valeurs</h2>
        </header>

        <p>
            Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté.
            Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs.
        </p>

        <p>
            Notre association a été fondée avec une conviction profonde :
            chaque livre mérite d'être lu et partagé.
        </p>

        <p>
            Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs
            de se connecter, de partager leurs découvertes littéraires et d'échanger des livres
            qui attendent patiemment sur les étagères.
        </p>

        <div class="home-values-signature">
            <p>L’équipe Tom Troc</p>
            <img src="/assets/hart.svg" alt="" aria-hidden="true">
        </div>
    </div>
</section>