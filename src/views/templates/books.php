<?php
/**
 * Variables injected by View::render() via extract($params).
 *
 * @var string|null $search
 * @var array<int, array<string, mixed>> $bookCards
 */
?>

<section class="books-page">
    <div class="books-page__inner site-frame">
        <header class="books-page__header">
            <h1 class="books-page__title">Nos livres à l’échange</h1>

            <form class="books-page__search-form" method="get">
                <input type="hidden" name="action" value="books">

                <label for="books-search" class="visually-hidden">Rechercher un livre</label>

                <div class="books-page__search-field">
                    <img
                        class="books-page__search-icon"
                        src="/assets/icon-search.svg"
                        alt=""
                        aria-hidden="true">

                    <input
                        class="books-page__search-input"
                        type="search"
                        id="books-search"
                        name="search"
                        placeholder="Rechercher un livre"
                        value="<?= htmlspecialchars((string) ($search ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </form>
        </header>

        <?php if (empty($bookCards)): ?>
            <p class="books-page__empty">
                <?= !empty($search)
                    ? 'Aucun livre ne correspond a votre recherche.'
                    : "Aucun livre n'est disponible pour le moment." ?>
            </p>
        <?php else: ?>
            <div class="books-page__grid books-grid">
                <?php foreach ($bookCards as $bookCard): ?>
                    <?php
                    $bookCardData = $bookCard;
                    $bookCardOwnerLabelPrefix = 'Vendu par:';
                    $bookCardShowStatusText = true;
                    $bookCardUnavailableText = 'non dispo.';
                    require __DIR__ . '/common/book-card.php';
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>