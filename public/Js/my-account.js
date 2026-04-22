document.addEventListener('DOMContentLoaded', () => {
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.getElementById('profile-image-preview');
    const messageBox = document.getElementById('my-account-message');

    const setAccountMessage = (message, state = '') => {
        if (!messageBox) {
            return;
        }

        messageBox.textContent = message;
        messageBox.dataset.state = state;
        messageBox.hidden = message === '';
    };

    if (profileImageInput && profileImagePreview) {
        profileImageInput.addEventListener('change', () => {
            const file = profileImageInput.files && profileImageInput.files[0];

            if (!file) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            profileImagePreview.src = previewUrl;
            profileImagePreview.style.display = 'block';
        });
    }

    initFormAjax(
        'my-account-form',
        (data) => {
            setAccountMessage('', '');

            if (data.success) {
                setAccountMessage(data.data?.message || 'Profil mis à jour.', 'success');

                return;
            }

            setAccountMessage(data.error || 'La mise à jour a échoué.', 'error');
        },
        (error) => {
            console.error('Erreur AJAX My Account :', error);

            setAccountMessage('Erreur réseau ou serveur.', 'error');
        }
    );
});