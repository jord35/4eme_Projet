document.addEventListener('DOMContentLoaded', () => {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const usernameMsg = document.getElementById('username-message');
    const emailMsg = document.getElementById('email-message');
    const passwordMsg = document.getElementById('password-message');
    const formMessage = document.getElementById('signup-message');

    const setFieldMessage = (element, message, state = '') => {
        if (!element) {
            return;
        }

        element.textContent = message;
        element.dataset.state = state;
        element.hidden = message === '';
    };

    const setFormMessage = (message, state = '') => {
        if (!formMessage) {
            return;
        }

        formMessage.textContent = message;
        formMessage.dataset.state = state;
        formMessage.hidden = message === '';
    };

    async function checkUsername() {
        const username = usernameInput.value.trim();
        setFieldMessage(usernameMsg, '', '');

        if (!username) {
            return;
        }

        try {
            const response = await fetch(
                `/?action=signup-check-username&username=${encodeURIComponent(username)}`,
                {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            setFieldMessage(usernameMsg, data.message || '', data.success ? 'success' : 'error');
        } catch (error) {
            console.error(error);
        }
    }

    async function checkEmail() {
        const email = emailInput.value.trim();
        setFieldMessage(emailMsg, '', '');

        if (!email) {
            return;
        }

        try {
            const response = await fetch(
                `/?action=signup-check-email&email=${encodeURIComponent(email)}`,
                {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            setFieldMessage(emailMsg, data.message || '', data.success ? 'success' : 'error');
        } catch (error) {
            console.error(error);
        }
    }

    usernameInput.addEventListener('blur', checkUsername);
    emailInput.addEventListener('blur', checkEmail);

    initFormAjax('signup-form', (data) => {
        setFieldMessage(usernameMsg, '', '');
        setFieldMessage(emailMsg, '', '');
        setFieldMessage(passwordMsg, '', '');
        setFormMessage('', '');

        if (data.success) {
            setFormMessage(data.message || 'Inscription réussie.', 'success');
            return;
        }

        if (data.errors && data.errors.username) {
            setFieldMessage(usernameMsg, data.errors.username, 'error');
        }

        if (data.errors && data.errors.email) {
            setFieldMessage(emailMsg, data.errors.email, 'error');
        }

        if (data.errors && data.errors.password) {
            setFieldMessage(passwordMsg, data.errors.password, 'error');
        }

        if (data.message) {
            setFormMessage(data.message, 'error');
            console.error(data.message);
        }
    }, (error) => {
        console.error('Erreur AJAX signup :', error);
        setFormMessage('Erreur réseau ou serveur.', 'error');
    });
});