
<article class="message" data-message-id="<?= (int) $message->getId() ?>">
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