document.addEventListener('DOMContentLoaded', () => {
    const messagesList = document.getElementById('messages-list');
    const messageForm = document.getElementById('message-form');
    const feedback = document.getElementById('message-form-feedback');
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
            navbarBadge.classList.remove('is-hidden');
            navbarBadge.setAttribute('aria-hidden', 'false');
        } else {
            navbarBadge.classList.add('is-hidden');
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

    function formatMessageHtml(message) {
        const isOwn = message.is_own ? ' is-own' : '';

        return `
            <article class="message-item${isOwn}" data-message-id="${Number(message.id)}">
                <header class="message-item__header">
                    <strong>${escapeHtml(message.sender_username)}</strong>
                    <small>${escapeHtml(message.created_at)}</small>
                </header>
                <p>${escapeHtml(message.content).replace(/\n/g, '<br>')}</p>
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

        if (!Array.isArray(conversationSummaries) || conversationSummaries.length === 0) {
            conversationList.innerHTML = '';
            return;
        }

        conversationList.innerHTML = conversationSummaries.map((conversation) => {
            const conversationId = Number(conversation.conversation_id || 0);
            const isActive = conversationId === activeConversationId;
            const unreadCount = Number(conversation.unread_count || 0);
            const hasUnread = unreadCount > 0;

            const classes = [
                'messages-conversation-item',
                isActive ? 'is-active' : '',
                hasUnread ? 'has-unread' : ''
            ].filter(Boolean).join(' ');

            const lastMessageContent = conversation.last_message_content
                ? escapeHtml(conversation.last_message_content)
                : 'Aucun message pour le moment.';

            const lastMessageCreatedAt = conversation.last_message_created_at
                ? `<small>${escapeHtml(conversation.last_message_created_at)}</small>`
                : '';

            const badge = hasUnread
                ? `<span class="messages-conversation-badge">${unreadCount}</span>`
                : '';

            return `
                <li class="${classes}">
                    <a href="/?action=messages&conversation_id=${conversationId}">
                        <div>
                            <strong>${escapeHtml(conversation.other_username || '')}</strong>
                        </div>
                        <p>${lastMessageContent}</p>
                        ${lastMessageCreatedAt}
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
            if (feedback) {
                feedback.textContent = '';
                feedback.className = '';
            }

            if (!data || data.success !== true) {
                if (feedback) {
                    feedback.textContent = data?.error || 'Le message n’a pas pu être envoyé.';
                    feedback.className = 'error-message';
                }
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

            if (feedback) {
                feedback.textContent = 'Message envoyé.';
                feedback.className = 'success-message';
            }

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

            if (feedback) {
                feedback.textContent = 'Erreur réseau ou serveur.';
                feedback.className = 'error-message';
            }
        }
    );

    poller.start(3000);
    conversationSummariesPoller.start(3000);
});