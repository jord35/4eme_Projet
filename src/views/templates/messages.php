<section class="messages-page">
    <header class="messages-page__header">
        <div>
            <h1>Messages</h1>
            <p>Retrouve ici tes conversations.</p>
        </div>

        <div>
            <strong>Non lus :</strong> <span id="unread-conversation-count"><?= (int) $unreadConversationCount ?></span>
        </div>
    </header>

    <div
        class="messages-layout"
        id="messages-page"
        data-active-conversation-id="<?= $activeConversationId !== null ? (int) $activeConversationId : 0 ?>"
    >
        <aside class="messages-sidebar">
            <h2>Conversations</h2>

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
                        ?>
                        <li class="messages-conversation-item<?= $isActive ? ' is-active' : '' ?><?= $hasUnread ? ' has-unread' : '' ?>">
                            <a href="/?action=messages&conversation_id=<?= $conversationId ?>">
                                <div>
                                    <strong><?= htmlspecialchars((string) $conversation['other_username'], ENT_QUOTES, 'UTF-8') ?></strong>
                                </div>

                                <?php if (!empty($conversation['last_message_content'])): ?>
                                    <p><?= htmlspecialchars((string) $conversation['last_message_content'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php else: ?>
                                    <p>Aucun message pour le moment.</p>
                                <?php endif; ?>

                                <?php if (!empty($conversation['last_message_created_at'])): ?>
                                    <small><?= htmlspecialchars((string) $conversation['last_message_created_at'], ENT_QUOTES, 'UTF-8') ?></small>
                                <?php endif; ?>

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
                <p>Sélectionne une conversation pour afficher les messages.</p>
            <?php else: ?>
                <?php
                    $lastMessageId = 0;

                    if (!empty($messages)) {
                        $lastMessage = end($messages);
                        $lastMessageId = (int) ($lastMessage['id'] ?? 0);
                    }
                ?>

                <div
                    id="messages-list"
                    class="messages-list"
                    data-conversation-id="<?= (int) $activeConversationId ?>"
                    data-last-message-id="<?= $lastMessageId ?>"
                    data-current-user-id="<?= (int) $currentUserId ?>"
                >
                    <?php if (empty($messages)): ?>
                        <p id="empty-messages">Aucun message dans cette conversation pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                            <?php
                                $isOwnMessage = (int) $message['sender_user_id'] === (int) $currentUserId;
                            ?>
                            <article class="message-item<?= $isOwnMessage ? ' is-own' : '' ?>" data-message-id="<?= (int) $message['id'] ?>">
                                <header class="message-item__header">
                                    <strong><?= htmlspecialchars((string) $message['sender_username'], ENT_QUOTES, 'UTF-8') ?></strong>
                                    <small><?= htmlspecialchars((string) $message['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
                                </header>

                                <p><?= nl2br(htmlspecialchars((string) $message['content'], ENT_QUOTES, 'UTF-8')) ?></p>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form
                    id="message-form"
                    method="POST"
                    action="/?action=messages"
                    class="message-form"
                >
                    <input
                        type="hidden"
                        name="conversation_id"
                        value="<?= (int) $activeConversationId ?>"
                    >

                    <div>
                        <label for="content">Nouveau message</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="4"
                            placeholder="Écris ton message..."
                            required
                        ></textarea>
                    </div>

                    <button type="submit">Envoyer</button>
                </form>

                <p id="message-form-feedback"></p>
            <?php endif; ?>
        </section>
    </div>
</section>

<script src="/js/messages.js"></script>