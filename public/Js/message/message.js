

document.addEventListener('DOMContentLoaded', () => {
    const messagesList = document.getElementById('messages-list');

    if (!messagesList) {
        return;
    }

    let lastId = Number(messagesList.dataset.lastId || 0);

    function getUrl() {
        return `index.php?ajax=messages&afterId=${lastId}`;
    }

    function handleNewMessages(data) {
        console.log('Données reçues :', data);
        if (!data.html || data.html.trim() === '') {
            return;
        }

        const emptyMessage = document.getElementById('empty-message');

        if (emptyMessage) {
            emptyMessage.remove();
        }

        messagesList.insertAdjacentHTML('beforeend', data.html);

        lastId = Number(data.lastId || lastId);
        messagesList.dataset.lastId = String(lastId);
    }

    const poller = createPoller({
        urlFn: getUrl,
        onSuccess: handleNewMessages,
        onError: (error) => {
            console.error('Erreur lors du chargement des messages :', error);
        }
    });

    poller.start(3000);

    initFormAjax({
        formId: 'message-form',
        onSuccess: ({ form }) => {
            form.reset();
            poller.runOnce();
        },
        onError: (error) => {
            console.error('Erreur lors de l’envoi du message :', error);
        }
    });
});
