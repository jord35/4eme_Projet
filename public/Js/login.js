document.addEventListener('DOMContentLoaded', () => {
    initFormAjax({
        formId: 'login-form',

        onSuccess: ({ data, form, response }) => {
            console.log('Réponse login OK :', data);
            console.log('HTTP status :', response.status);

            const messageBox = document.getElementById('login-message');

            if (messageBox) {
                messageBox.textContent = '';
                messageBox.className = '';
            }

            if (data.success) {
                if (messageBox) {
                    messageBox.textContent = 'Connexion réussie.';
                    messageBox.className = 'success-message';
                }

                console.log('Connexion réussie');
            } else {
                if (messageBox) {
                    messageBox.textContent = data.message || 'Échec de la connexion.';
                    messageBox.className = 'error-message';
                }

                console.log('Connexion refusée');
            }
        },

        onError: (error) => {
            console.error('Erreur AJAX login :', error);

            const messageBox = document.getElementById('login-message');

            if (messageBox) {
                messageBox.textContent = 'Erreur réseau ou serveur.';
                messageBox.className = 'error-message';
            }
        }
    });
});