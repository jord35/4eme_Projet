<section class="messages-page">
    <div
        class="messages-layout"
        id="messages-page"
        data-active-conversation-id="<?= $activeConversationId !== null ? (int) $activeConversationId : 0 ?>">
        <aside class="messages-sidebar">

            <h1>Messagerie</h1>



            <?php if (empty($conversationSummaries)): ?>
                <p>Aucune conversation pour le moment.</p>
            <?php else: ?>
                <ul id="messages-conversation-list" class="messages-conversation-list">
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
                        <li class="messages-conversation-item<?= $isActive ? ' is-active' : '' ?><?= $hasUnread ? ' has-unread' : '' ?>">
                            <a
                                class="conversation-card"
                                href="/?action=messages&conversation_id=<?= $conversationId ?>">
                                <img
                                    src="<?= htmlspecialchars($conversationAvatar, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Photo de profil de <?= htmlspecialchars((string) $conversation['other_username'], ENT_QUOTES, 'UTF-8') ?>"
                                    width="56"
                                    height="56"
                                    onerror="this.onerror=null;this.src='<?= htmlspecialchars($fallbackConversationAvatar, ENT_QUOTES, 'UTF-8') ?>';">

                                <div class="conversation-card-body">
                                    <div class="conversation-card-header">
                                        <h2><?= htmlspecialchars((string) $conversation['other_username'], ENT_QUOTES, 'UTF-8') ?></h2>

                                        <?php if (!empty($conversation['last_message_created_at'])): ?>
                                            <p><?= htmlspecialchars((string) $conversation['last_message_created_at'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($conversation['last_message_content'])): ?>
                                        <p><?= htmlspecialchars((string) $conversation['last_message_content'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php else: ?>
                                        <p>Aucun message pour le moment.</p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($hasUnread): ?>
                                    <span class="messages-conversation-badge"><?= $unreadCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </aside>

        <section class="messages-main">
            <?php if ($activeConversationId === null): ?>
                <div class="messages-empty-state">
                    <p>Sélectionne une conversation pour afficher les messages.</p>
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

                <div class="messages-main-header">
                    <img
                        src="<?= htmlspecialchars($activeConversationAvatar, ENT_QUOTES, 'UTF-8') ?>"
                        alt="Photo de profil de <?= htmlspecialchars($activeConversationUsername, ENT_QUOTES, 'UTF-8') ?>"
                        width="56"
                        height="56"
                        onerror="this.onerror=null;this.src='/assets/Icon-mon-compte.svg';">

                    <div>
                        <h2><?= htmlspecialchars($activeConversationUsername, ENT_QUOTES, 'UTF-8') ?></h2>
                    </div>
                </div>

                <div
                    id="messages-list"
                    class="messages-list"
                    data-conversation-id="<?= (int) $activeConversationId ?>"
                    data-last-message-id="<?= $lastMessageId ?>"
                    data-current-user-id="<?= (int) $currentUserId ?>">
                    <?php if (empty($messages)): ?>
                        <p id="empty-messages">Aucun message dans cette conversation pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                            <?php $isOwnMessage = (int) $message['sender_user_id'] === (int) $currentUserId; ?>

                            <article class="message-item<?= $isOwnMessage ? ' is-own' : '' ?>" data-message-id="<?= (int) $message['id'] ?>">
                                <?php if ($isOwnMessage): ?>
                                    <p class="message-item-time">
                                        <?= htmlspecialchars((string) $message['created_at'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>

                                    <div class="message-item-bubble">
                                        <p><?= nl2br(htmlspecialchars((string) $message['content'], ENT_QUOTES, 'UTF-8')) ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="message-item-meta">
                                        <img
                                            src="<?= htmlspecialchars($activeConversationAvatar, ENT_QUOTES, 'UTF-8') ?>"
                                            alt=""
                                            aria-hidden="true"
                                            width="28"
                                            height="28"
                                            onerror="this.onerror=null;this.src='/assets/Icon-mon-compte.svg';">
                                        <p><?= htmlspecialchars((string) $message['created_at'], ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>

                                    <div class="message-item-bubble">
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
                    class="message-form">
                    <input
                        type="hidden"
                        name="conversation_id"
                        value="<?= (int) $activeConversationId ?>">

                    <div class="message-form-field">
                        <label for="content" class="visually-hidden">Nouveau message</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="3"
                            placeholder="Tapez votre message ici"
                            required></textarea>
                    </div>

                    <button type="submit">Envoyer</button>
                </form>

                <p id="message-form-feedback"></p>
            <?php endif; ?>
        </section>
    </div>
</section>

<script src="/js/messages.js"></script>