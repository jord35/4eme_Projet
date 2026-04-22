document.addEventListener('DOMContentLoaded', () => {
    const setLoginMessage = (messageBox, message, state) => {
        if (!messageBox) {
            return;
        }

        messageBox.textContent = message;
        messageBox.dataset.state = state;
        messageBox.hidden = message === '';
    };

    initFormAjax(
        'login-form',
        (data, form, response) => {
            console.log('Réponse login OK :', data);
            console.log('HTTP status :', response.status);

            const messageBox = document.getElementById('login-message');

            setLoginMessage(messageBox, '', '');

            if (data.success) {
                setLoginMessage(messageBox, 'Connexion réussie.', 'success');

                console.log('Connexion réussie');
            } else {
                setLoginMessage(messageBox, data.message || 'Échec de la connexion.', 'error');

                console.log('Connexion refusée');
            }
        },
        (error) => {
            console.error('Erreur AJAX login :', error);

            const messageBox = document.getElementById('login-message');

            setLoginMessage(messageBox, 'Erreur réseau ou serveur.', 'error');
        }
    );
});