<section class="home-hero">
    <div class="home-hero__inner site-frame">
        <div class="home-hero__content">
            <h1 class="home-hero__title">Rejoignez nos lecteurs passionnés</h1>

            <p class="home-hero__text">
                Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture.
                Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
            </p>

            <a class="home-hero__cta" href="/?action=books">Découvrir</a>
        </div>

        <div class="home-hero__media">
            <img
                class="home-hero__image"
                src="/assets/hamza-nouasria-desktop.webp"
                alt="Personne dans une librairie entourée de livres">

            <p class="home-hero__credit">Hamza</p>
        </div>
    </div>
</section>

<section class="home-latest-books">
    <div class="home-latest-books__inner site-frame">
        <?php
        $sectionHeaderTitle = 'Les derniers livres ajoutés';
        $sectionHeaderClassName = 'home-latest-books__header';
        $sectionHeaderAlignment = 'center';
        require __DIR__ . '/common/section-header.php';
        ?>

        <?php if (empty($bookCards)): ?>
            <p class="home-latest-books__empty">Aucun livre n'est disponible pour le moment.</p>
        <?php else: ?>
            <div class="books-grid home-latest-books__grid">
                <?php foreach ($bookCards as $bookCard): ?>
                    <?php
                    $bookCardData = $bookCard;
                    $bookCardOwnerLabelPrefix = 'Proposé par';
                    $bookCardShowStatusText = false;
                    require __DIR__ . '/common/book-card.php';
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p class="home-latest-books__footer">
            <a class="home-latest-books__cta" href="/?action=books">Voir tous les livres</a>
        </p>
    </div>
</section>

<section class="home-how-it-works">
    <div class="home-how-it-works__inner site-frame">
        <?php
        $sectionHeaderTitle = 'Comment ça marche ?';
        $sectionHeaderText = 'Échanger des livres avec Tom Troc, c’est simple et amusant. Suivez ces étapes pour commencer.';
        $sectionHeaderClassName = 'home-how-it-works__header';
        $sectionHeaderAlignment = 'center';
        require __DIR__ . '/common/section-header.php';
        ?>

        <div class="steps-list home-how-it-works__steps">
            <article class="step-card">
                <p class="step-card__text">Inscrivez-vous gratuitement sur notre plateforme.</p>
            </article>

            <article class="step-card">
                <p class="step-card__text">Ajoutez les livres que vous souhaitez échanger à votre profil.</p>
            </article>

            <article class="step-card">
                <p class="step-card__text">Parcourez les livres disponibles chez d'autres membres.</p>
            </article>

            <article class="step-card">
                <p class="step-card__text">Proposez un échange et discutez avec d'autres passionnés de lecture.</p>
            </article>
        </div>

        <p class="home-how-it-works__footer">
            <a class="home-how-it-works__cta" href="/?action=books">Voir tous les livres</a>
        </p>
    </div>
</section>

<section class="home-values">
    <div class="home-values__media">
        <img
            class="home-values__image"
            src="/assets/Mask-group-desktop.webp"
            alt="">
    </div>

    <div class="home-values__inner site-frame">
        <div class="home-values__content">
            <?php
            $sectionHeaderTitle = 'Nos valeurs';
            $sectionHeaderText = null;
            $sectionHeaderClassName = 'home-values__header';
            $sectionHeaderAlignment = 'left';
            require __DIR__ . '/common/section-header.php';
            ?>

            <div class="home-values__body">
                <p class="home-values__text">
                    Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté.
                    Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs.
                </p>

                <p class="home-values__text">
                    Notre association a été fondée avec une conviction profonde :
                    chaque livre mérite d'être lu et partagé.
                </p>

                <p class="home-values__text">
                    Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs
                    de se connecter, de partager leurs découvertes littéraires et d'échanger des livres
                    qui attendent patiemment sur les étagères.
                </p>
            </div>

            <div class="home-values__signature">
                <p class="home-values__signature-text">L’équipe Tom Troc</p>
                <img class="home-values__signature-icon" src="/assets/hart.svg" alt="" aria-hidden="true">
            </div>
        </div>
    </div>
</section>