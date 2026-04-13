document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit-book-form');
    const pictureInput = document.getElementById('picture');
    const preview = document.getElementById('picture-preview');
    const messageBox = document.getElementById('book-form-message');

    if (!form || !preview || typeof initFormAjax !== 'function') {
        return;
    }

    let previewObjectUrl = null;

    function showMessage(message, type = 'success') {
        if (!messageBox) {
            return;
        }

        messageBox.textContent = message;
        messageBox.dataset.state = type;
        messageBox.hidden = false;
    }

    function clearMessage() {
        if (!messageBox) {
            return;
        }

        messageBox.textContent = '';
        messageBox.dataset.state = '';
        messageBox.hidden = true;
    }

    function updatePreviewFromServer(coverPicture) {
        if (!coverPicture) {
            return;
        }

        preview.src = coverPicture.src || '';

        if (coverPicture.srcset) {
            preview.setAttribute('srcset', coverPicture.srcset);
        } else {
            preview.removeAttribute('srcset');
        }

        if (coverPicture.sizes) {
            preview.setAttribute('sizes', coverPicture.sizes);
        } else {
            preview.removeAttribute('sizes');
        }

        preview.alt = coverPicture.alt || 'Aperçu de la couverture';
        preview.width = Number(coverPicture.width || 220);
        preview.height = Number(coverPicture.height || 320);
        preview.style.display = 'block';
    }

    if (pictureInput) {
        pictureInput.addEventListener('change', () => {
            const file = pictureInput.files?.[0];

            clearMessage();

            if (!file) {
                return;
            }

            if (previewObjectUrl) {
                URL.revokeObjectURL(previewObjectUrl);
            }

            previewObjectUrl = URL.createObjectURL(file);
            preview.src = previewObjectUrl;
            preview.style.display = 'block';
            preview.removeAttribute('srcset');
            preview.removeAttribute('sizes');
            preview.alt = "Aperçu local de l'image sélectionnée";
        });
    }

    initFormAjax(
        'edit-book-form',
        (data, form) => {
            if (!data || data.success === false) {
                showMessage(data?.error || 'Une erreur est survenue.', 'error');
                return;
            }

            const savedBook = data.data?.book ?? null;
            const coverPicture = data.data?.coverPicture ?? null;

            if (savedBook?.id) {
                const hiddenId = form.querySelector('input[name="id"]');

                if (hiddenId) {
                    hiddenId.value = String(savedBook.id);
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = String(savedBook.id);
                    form.prepend(input);
                }
            }

            if (coverPicture) {
                updatePreviewFromServer(coverPicture);
            }

            if (previewObjectUrl) {
                URL.revokeObjectURL(previewObjectUrl);
                previewObjectUrl = null;
            }

            showMessage(data.data?.message || 'Livre enregistré avec succès.', 'success');
        },
        (error) => {
            showMessage(error.message || 'Erreur réseau.', 'error');
        }
    );
});