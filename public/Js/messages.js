document.addEventListener('DOMContentLoaded', () => {
    const messagesList = document.getElementById('messages-list');
    const messageForm = document.getElementById('message-form');
    const navbarBadge = document.getElementById('navbar-message-badge');
    const conversationList = document.getElementById('messages-conversation-list');
    const unreadConversationCountElement = document.getElementById('unread-conversation-count');

    if (!messagesList || !messageForm) {
        return;
    }

    function getConversationId() {
        return Number(messagesList.dataset.conversationId || 0);
    }

    function getLastMessageId() {
        return Number(messagesList.dataset.lastMessageId || 0);
    }

    function setLastMessageId(messageId) {
        messagesList.dataset.lastMessageId = String(messageId);
    }

    function updateNavbarBadge(nextCount) {
        if (!navbarBadge) {
            return;
        }

        const count = Number(nextCount || 0);
        const suffix = count > 1 ? 's' : '';

        navbarBadge.textContent = String(count);
        navbarBadge.setAttribute('aria-label', `${count} message${suffix} non lu${suffix}`);

        if (count > 0) {
            navbarBadge.hidden = false;
            navbarBadge.setAttribute('aria-hidden', 'false');
        } else {
            navbarBadge.hidden = true;
            navbarBadge.setAttribute('aria-hidden', 'true');
        }
    }

    function updateUnreadConversationCount(nextCount) {
        if (!unreadConversationCountElement) {
            return;
        }

        unreadConversationCountElement.textContent = String(Number(nextCount || 0));
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function getActiveConversationAvatarSrc() {
        const threadAvatar = document.querySelector('.messages-page__thread-card img');

        if (!threadAvatar) {
            return '/assets/Icon-mon-compte.svg';
        }

        return threadAvatar.getAttribute('src') || '/assets/Icon-mon-compte.svg';
    }

    function formatMessageHtml(message) {
        const isOwn = Boolean(message.is_own);
        const messageClasses = isOwn
            ? 'messages-page__message messages-page__message--own'
            : 'messages-page__message';
        const contentHtml = escapeHtml(message.content).replace(/\n/g, '<br>');

        if (isOwn) {
            return `
                <article class="${messageClasses}" data-message-id="${Number(message.id)}">
                    <p class="messages-page__message-time">
                        ${escapeHtml(message.created_at)}
                    </p>

                    <div class="messages-page__message-bubble">
                        <p>${contentHtml}</p>
                    </div>
                </article>
            `;
        }

        const avatarSrc = escapeHtml(getActiveConversationAvatarSrc());

        return `
            <article class="${messageClasses}" data-message-id="${Number(message.id)}">
                <div class="messages-page__message-meta">
                    <img
                        class="messages-page__message-avatar avatar avatar--xs"
                        src="${avatarSrc}"
                        alt=""
                        aria-hidden="true"
                        width="28"
                        height="28"
                        onerror="this.onerror=null;this.src='/assets/Icon-mon-compte.svg';">
                    <p class="messages-page__message-time">
                        ${escapeHtml(message.created_at)}
                    </p>
                </div>

                <div class="messages-page__message-bubble">
                    <p>${contentHtml}</p>
                </div>
            </article>
        `;
    }

    function appendMessages(messages) {
        if (!Array.isArray(messages) || messages.length === 0) {
            return;
        }

        const emptyState = document.getElementById('empty-messages');

        if (emptyState) {
            emptyState.remove();
        }

        messages.forEach((message) => {
            const existing = messagesList.querySelector(`[data-message-id="${Number(message.id)}"]`);

            if (existing) {
                return;
            }

            const html = formatMessageHtml(message);
            messagesList.insertAdjacentHTML('beforeend', html);
            setLastMessageId(Number(message.id));
        });

        messagesList.scrollTop = messagesList.scrollHeight;
    }

    function renderConversationSummaries(conversationSummaries) {
        if (!conversationList) {
            return;
        }

        const activeConversationId = getConversationId();
        const fallbackConversationAvatar = '/assets/Icon-mon-compte.svg';

        if (!Array.isArray(conversationSummaries) || conversationSummaries.length === 0) {
            conversationList.innerHTML = '<li class="messages-page__empty-sidebar">Aucune conversation pour le moment.</li>';
            return;
        }

        conversationList.innerHTML = conversationSummaries.map((conversation) => {
            const conversationId = Number(conversation.conversation_id || 0);
            const isActive = conversationId === activeConversationId;
            const unreadCount = Number(conversation.unread_count || 0);
            const hasUnread = unreadCount > 0;
            const conversationAvatar = conversation.other_user_picture
                ? escapeHtml(conversation.other_user_picture)
                : fallbackConversationAvatar;
            const conversationUsername = escapeHtml(conversation.other_username || '');

            const classes = [
                'messages-page__conversation-item',
                isActive ? 'messages-page__conversation-item--active' : '',
                hasUnread ? 'messages-page__conversation-item--unread' : ''
            ].filter(Boolean).join(' ');

            const lastMessageContent = conversation.last_message_content
                ? escapeHtml(conversation.last_message_content)
                : 'Aucun message pour le moment.';

            const lastMessageCreatedAt = conversation.last_message_created_at
                ? `<p class="messages-page__conversation-time">${escapeHtml(conversation.last_message_created_at)}</p>`
                : '';

            const badge = hasUnread
                ? `<span class="messages-page__conversation-badge">${unreadCount}</span>`
                : '';

            return `
                <li class="${classes}">
                    <a
                        class="messages-page__conversation-card"
                        href="/?action=messages&conversation_id=${conversationId}">
                        <img
                            class="messages-page__conversation-avatar avatar avatar--md"
                            src="${conversationAvatar}"
                            alt="Photo de profil de ${conversationUsername}"
                            width="56"
                            height="56"
                            onerror="this.onerror=null;this.src='${fallbackConversationAvatar}';">
                        <div class="messages-page__conversation-body">
                            <div class="messages-page__conversation-header">
                                <h2 class="messages-page__conversation-name">${conversationUsername}</h2>
                                ${lastMessageCreatedAt}
                            </div>
                            <p class="messages-page__conversation-preview">${lastMessageContent}</p>
                        </div>
                        ${badge}
                    </a>
                </li>
            `;
        }).join('');
    }

    const poller = createPoller({
        urlFn: () => {
            const conversationId = getConversationId();
            const afterId = getLastMessageId();

            return `/?action=messages&ajax=updates&conversation_id=${conversationId}&after_id=${afterId}`;
        },
        onSuccess: (response) => {
            if (!response || response.success !== true) {
                return;
            }

            appendMessages(response.data?.messages || []);

            if (typeof response.data?.unreadMessageCount !== 'undefined') {
                updateNavbarBadge(response.data.unreadMessageCount);
            }

            if (typeof response.data?.unreadConversationCount !== 'undefined') {
                updateUnreadConversationCount(response.data.unreadConversationCount);
            }

            conversationSummariesPoller.runOnce();
        },
        onError: (error) => {
            console.error('Erreur lors du chargement des nouveaux messages :', error);
        }
    });

    const conversationSummariesPoller = createPoller({
        urlFn: () => '/?action=messages&ajax=conversation-summaries',
        onSuccess: (response) => {
            if (!response || response.success !== true) {
                return;
            }

            renderConversationSummaries(response.data?.conversationSummaries || []);

            if (typeof response.data?.unreadMessageCount !== 'undefined') {
                updateNavbarBadge(response.data.unreadMessageCount);
            }

            if (typeof response.data?.unreadConversationCount !== 'undefined') {
                updateUnreadConversationCount(response.data.unreadConversationCount);
            }
        },
        onError: (error) => {
            console.error('Erreur lors du chargement des conversations :', error);
        }
    });

    initFormAjax(
        'message-form',
        (data, form) => {
            if (!data || data.success !== true) {
                console.error(data?.error || 'Le message n’a pas pu être envoyé.');
                return;
            }

            const message = data.data?.message || null;

            if (message) {
                appendMessages([
                    {
                        ...message,
                        is_own: true
                    }
                ]);
            }

            form.reset();

            if (typeof data.data?.unreadMessageCount !== 'undefined') {
                updateNavbarBadge(data.data.unreadMessageCount);
            }

            if (typeof data.data?.unreadConversationCount !== 'undefined') {
                updateUnreadConversationCount(data.data.unreadConversationCount);
            }

            conversationSummariesPoller.runOnce();
            poller.runOnce();
        },
        (error) => {
            console.error('Erreur lors de l’envoi du message :', error);
        }
    );

    poller.start(3000);
    conversationSummariesPoller.start(3000);
});