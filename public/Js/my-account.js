document.addEventListener('DOMContentLoaded', () => {
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.getElementById('profile-image-preview');
    const noProfileImage = document.getElementById('no-profile-image');
    const messageBox = document.getElementById('my-account-message');

    if (profileImageInput && profileImagePreview) {
        profileImageInput.addEventListener('change', () => {
            const file = profileImageInput.files && profileImageInput.files[0];

            if (!file) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            profileImagePreview.src = previewUrl;
            profileImagePreview.style.display = 'block';

            if (noProfileImage) {
                noProfileImage.style.display = 'none';
            }
        });
    }

    initFormAjax({
        formId: 'my-account-form',

        onSuccess: ({ data }) => {
            if (messageBox) {
                messageBox.textContent = '';
                messageBox.className = '';
            }

            if (data.success) {
                if (messageBox) {
                    messageBox.textContent = data.data?.message || 'Profil mis à jour.';
                    messageBox.className = 'success-message';
                }

                return;
            }

            if (messageBox) {
                messageBox.textContent = data.error || 'La mise à jour a échoué.';
                messageBox.className = 'error-message';
            }
        },

        onError: (error) => {
            console.error('Erreur AJAX My Account :', error);

            if (messageBox) {
                messageBox.textContent = 'Erreur réseau ou serveur.';
                messageBox.className = 'error-message';
            }
        }
    });
});