document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('my-account-form');
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.getElementById('profile-image-preview');
    const profileName = document.getElementById('my-account-profile-name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const navUsername = document.getElementById('site-header-username');
    const messageBox = document.getElementById('my-account-message');

    if (!form || typeof initFormAjax !== 'function') {
        return;
    }

    const setAccountMessage = (message, state = '') => {
        if (!messageBox) {
            return;
        }

        messageBox.textContent = message;
        messageBox.dataset.state = state;
        messageBox.hidden = message === '';
    };

    function updateProfileImage(profilePicture, username = '') {
        if (!profileImagePreview) {
            return;
        }

        const fallbackSrc = profileImagePreview.dataset.fallbackSrc || '';
        const fallbackAlt = username !== ''
            ? `Image de profil de ${username}`
            : 'Image de profil utilisateur';

        applyResponsiveImageData(profileImagePreview, profilePicture, {
            fallbackSrc,
            fallbackAlt,
            defaultWidth: 200,
            defaultHeight: 200
        });
    }

    function applyProfileData(profile, profilePicture) {
        if (!profile) {
            return;
        }

        const username = String(profile.username || '');
        const email = String(profile.email || '');

        if (profileName) {
            profileName.textContent = username;
        }

        if (usernameInput) {
            usernameInput.value = username;
        }

        if (emailInput) {
            emailInput.value = email;
        }

        if (navUsername) {
            navUsername.textContent = username;
        }

        updateProfileImage(profilePicture, username);
    }

    const localPreview = bindImagePreview({
        input: profileImageInput,
        preview: profileImagePreview,
        onBeforeChange: () => setAccountMessage('', ''),
        localAlt: "Aperçu local de l'image de profil sélectionnée"
    });

    initFormAjax(
        'my-account-form',
        (data) => {
            setAccountMessage('', '');

            if (data.success) {
                applyProfileData(data.data?.profile ?? null, data.data?.profilePicture ?? null);

                if (passwordInput) {
                    passwordInput.value = '';
                }

                localPreview.release();
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