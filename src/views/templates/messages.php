<?php
/**
 * Variables injected by View::render() via extract($params).
 *
 * @var int $currentUserId
 * @var int|null $activeConversationId
 * @var array<int, array<string, mixed>> $conversationSummaries
 * @var array<int, array<string, mixed>> $messages
 */
?>

<section class="messages-page">
    <div
        class="messages-page__layout"
        id="messages-page"
        data-active-conversation-id="<?= $activeConversationId !== null ? (int) $activeConversationId : 0 ?>">

        <aside class="messages-page__sidebar">
            <h1 class="messages-page__title">Messagerie</h1>

            <?php if (empty($conversationSummaries)): ?>
                <p class="messages-page__empty-sidebar">Aucune conversation pour le moment.</p>
            <?php else: ?>
                <ul id="messages-conversation-list" class="messages-page__conversation-list">
                    <?php foreach ($conversationSummaries as $conversation): ?>
                        <?php
                        $conversationId = (int) $conversation['conversation_id'];
                        $isActive = $activeConversationId !== null && $conversationId === (int) $activeConversationId;
                        $unreadCount = (int) ($conversation['unread_count'] ?? 0);
                        $hasUnread = $unreadCount > 0;

                        $fallbackConversationAvatar = '/assets/Icon-mon-compte.svg';
                        $conversationAvatar = !empty($conversation['other_user_picture'])
                            ? (string) $conversation['other_user_picture']
                            : $fallbackConversationAvatar;
                        ?>
                        <li class="messages-page__conversation-item<?= $isActive ? ' messages-page__conversation-item--active' : '' ?><?= $hasUnread ? ' messages-page__conversation-item--unread' : '' ?>">
                            <a
                                class="messages-page__conversation-card"
                                href="/?action=messages&conversation_id=<?= $conversationId ?>">

                                <img
                                    class="messages-page__conversation-avatar avatar avatar--md"
                                    src="<?= htmlspecialchars($conversationAvatar, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Photo de profil de <?= htmlspecialchars((string) $conversation['other_username'], ENT_QUOTES, 'UTF-8') ?>"
                                    width="56"
                                    height="56"
                                    onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackConversationAvatar, ENT_QUOTES, 'UTF-8') ?>';">

                                <div class="messages-page__conversation-body">
                                    <div class="messages-page__conversation-header">
                                        <h2 class="messages-page__conversation-name">
                                            <?= htmlspecialchars((string) $conversation['other_username'], ENT_QUOTES, 'UTF-8') ?>
                                        </h2>

                                        <?php if (!empty($conversation['last_message_created_at'])): ?>
                                            <p class="messages-page__conversation-time">
                                                <?= htmlspecialchars((string) $conversation['last_message_created_at'], ENT_QUOTES, 'UTF-8') ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($conversation['last_message_content'])): ?>
                                        <p class="messages-page__conversation-preview">
                                            <?= htmlspecialchars((string) $conversation['last_message_content'], ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="messages-page__conversation-preview">
                                            Aucun message pour le moment.
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($hasUnread): ?>
                                    <span class="messages-page__conversation-badge"><?= $unreadCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </aside>

        <section class="messages-page__main">
            <?php if ($activeConversationId === null): ?>
                <div class="messages-page__empty-state">
                    <p class="messages-page__empty-text">Sélectionne une conversation pour afficher les messages.</p>
                </div>
            <?php else: ?>
                <?php
                $lastMessageId = 0;

                if (!empty($messages)) {
                    $lastMessage = end($messages);
                    $lastMessageId = (int) ($lastMessage['id'] ?? 0);
                    reset($messages);
                }

                $activeConversationUsername = '';
                $activeConversationAvatar = '/assets/Icon-mon-compte.svg';

                foreach ($conversationSummaries as $conversation) {
                    if ((int) $conversation['conversation_id'] === (int) $activeConversationId) {
                        $activeConversationUsername = (string) ($conversation['other_username'] ?? '');
                        $activeConversationAvatar = !empty($conversation['other_user_picture'])
                            ? (string) $conversation['other_user_picture']
                            : '/assets/Icon-mon-compte.svg';
                        break;
                    }
                }
                ?>

                <div class="messages-page__thread">
                    <header class="messages-page__thread-header">
                        <?php
                        $ownerCardClassName = 'messages-page__thread-card owner-card';
                        $ownerCardHref = '/?action=public-account&username=' . urlencode($activeConversationUsername);
                        $ownerCardName = $activeConversationUsername;
                        $ownerCardAvatarSrc = $activeConversationAvatar;
                        $ownerCardAvatarAlt = 'Photo de profil de ' . $activeConversationUsername;
                        $ownerCardAvatarClassName = 'avatar avatar--md';
                        require __DIR__ . '/common/owner-card.php';
                        ?>
                    </header>

                    <div
                        id="messages-list"
                        class="messages-page__messages"
                        data-conversation-id="<?= (int) $activeConversationId ?>"
                        data-last-message-id="<?= $lastMessageId ?>"
                        data-current-user-id="<?= (int) $currentUserId ?>">

                        <?php if (empty($messages)): ?>
                            <p id="empty-messages" class="messages-page__messages-empty">
                                Aucun message dans cette conversation pour le moment.
                            </p>
                        <?php else: ?>
                            <?php foreach ($messages as $message): ?>
                                <?php $isOwnMessage = (int) $message['sender_user_id'] === (int) $currentUserId; ?>

                                <article class="messages-page__message<?= $isOwnMessage ? ' messages-page__message--own' : '' ?>" data-message-id="<?= (int) $message['id'] ?>">
                                    <?php if ($isOwnMessage): ?>
                                        <p class="messages-page__message-time">
                                            <?= htmlspecialchars((string) $message['created_at'], ENT_QUOTES, 'UTF-8') ?>
                                        </p>

                                        <div class="messages-page__message-bubble">
                                            <p><?= nl2br(htmlspecialchars((string) $message['content'], ENT_QUOTES, 'UTF-8')) ?></p>
                                        </div>
                                    <?php else: ?>
                                        <div class="messages-page__message-meta">
                                            <img
                                                class="messages-page__message-avatar avatar avatar--xs"
                                                src="<?= htmlspecialchars($activeConversationAvatar, ENT_QUOTES, 'UTF-8') ?>"
                                                alt=""
                                                aria-hidden="true"
                                                width="28"
                                                height="28"
                                                onerror="this.onerror=null;this.src='/assets/Icon-mon-compte.svg';">
                                            <p class="messages-page__message-time">
                                                <?= htmlspecialchars((string) $message['created_at'], ENT_QUOTES, 'UTF-8') ?>
                                            </p>
                                        </div>

                                        <div class="messages-page__message-bubble">
                                            <p><?= nl2br(htmlspecialchars((string) $message['content'], ENT_QUOTES, 'UTF-8')) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form
                        id="message-form"
                        method="post"
                        action="/?action=messages"
                        class="messages-page__composer">
                        <input
                            type="hidden"
                            name="conversation_id"
                            value="<?= (int) $activeConversationId ?>">

                        <div class="messages-page__composer-field form-field">
                            <label for="content" class="visually-hidden">Nouveau message</label>
                            <textarea
                                class="messages-page__composer-textarea"
                                id="content"
                                name="content"
                                rows="3"
                                placeholder="Tapez votre message ici"
                                required></textarea>
                        </div>

                        <button class="messages-page__composer-button button button--primary" type="submit">Envoyer</button>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    </div>
</section>

<script src="/js/messages.js"></script>

