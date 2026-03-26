<h1>Test d'insertion de messages</h1>

<div class="message__layout">
    <section class="message__panel">
        <h2>Ajouter un message</h2>

        <form id="message-form" method="POST" action="">
            <input type="text" name="title" placeholder="Titre" required>
            <textarea name="content" placeholder="Contenu" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </section>

    <aside class="panel right">
        <h2>Liste des messages</h2>

        <?php
            $lastId = !empty($messages) ? $messages[0]->getId() : 0;
        ?>

        <div id="messages-list" data-last-id="<?= $lastId ?>">
            <?php if (empty($messages)): ?>
                <p id="empty-message">Aucun message pour le moment.</p>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                    <?php require __DIR__ . '/_item.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>
</div>
