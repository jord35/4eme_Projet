document.addEventListener('DOMContentLoaded', () => {
    initFormAjax({
        formId: 'my-account-form',

        onSuccess: ({ data, form, response }) => {
            console.log('Réponse My Account OK :', data);
            console.log('HTTP status :', response.status);

            const messageBox = document.getElementById('my-account-message');

            if (messageBox) {
                messageBox.textContent = '';
                messageBox.className = '';
            }

            if (data.success) {
                if (messageBox) {
                    messageBox.textContent = data.message || 'Profil mis à jour.';
                    messageBox.className = 'success-message';
                }

                if (data.profileImageUrl) {
                    const profileImage = document.getElementById('profile-image-preview');
                    if (profileImage) {
                        profileImage.src = data.profileImageUrl;
                    }
                }

                console.log('Mise à jour réussie');
            } else {
                if (messageBox) {
                    messageBox.textContent = data.message || 'La mise à jour a échoué.';
                    messageBox.className = 'error-message';
                }

                console.log('Mise à jour refusée');
            }
        },

        onError: (error) => {
            console.error('Erreur AJAX My Account :', error);

            const messageBox = document.getElementById('my-account-message');

            if (messageBox) {
                messageBox.textContent = 'Erreur réseau ou serveur.';
                messageBox.className = 'error-message';
            }
        }
    });
});