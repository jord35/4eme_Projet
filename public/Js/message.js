document.addEventListener('DOMContentLoaded', () => {
    const messagesList = document.getElementById('messages-list');

    if (!messagesList) {
        return;
    }

    let lastId = Number(messagesList.dataset.lastId || 0);
    let isLoading = false;

    async function fetchNewMessages() {
        if (isLoading) {
            return;
        }

        isLoading = true;

        try {
            const response = await fetch(`index.php?ajax=messages&afterId=${lastId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (data.html && data.html.trim() !== '') {
                const emptyMessage = document.getElementById('empty-message');
                if (emptyMessage) {
                    emptyMessage.remove();
                }

                messagesList.insertAdjacentHTML('beforeend', data.html);
                lastId = Number(data.lastId || lastId);
                messagesList.dataset.lastId = String(lastId);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des nouveaux messages :', error);
        } finally {
            isLoading = false;
        }
    }

    setInterval(fetchNewMessages, 3000);
});