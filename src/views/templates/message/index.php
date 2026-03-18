

<h1>Test d'insertion de messages</h1>

<div class="message__layout">
    <section class="message__panel">
        <h2>Ajouter un message</h2>

        <form method="POST" action="">
            <input type="text" name="title" placeholder="Titre" required>
            <textarea name="content" placeholder="Contenu" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </section>

    <aside class="panel right">
        <h2>Liste des messages</h2>

        <?php if (empty($messages)): ?>
            <p>Aucun message pour le moment.</p>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <article class="message">
                    <h3 class="message__item--title">
                        #<?= htmlspecialchars((string) $message->getId()) ?>
                        - <?= htmlspecialchars($message->getTitle()) ?>
                    </h3>

                    <p><?= nl2br(htmlspecialchars($message->getContent())) ?></p>

                    <p class="meta">
                        Date création :
                        <?= $message->getDateCreation() ? $message->getDateCreation()->format('d/m/Y H:i:s') : 'N/A' ?>
                        <br>
                        Date update :
                        <?= $message->getDateUpdate() ? $message->getDateUpdate()->format('d/m/Y H:i:s') : 'N/A' ?>
                    </p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </aside>
</div>
