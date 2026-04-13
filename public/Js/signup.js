document.addEventListener('DOMContentLoaded', () => {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const usernameMsg = document.getElementById('username-message');
    const emailMsg = document.getElementById('email-message');
    const passwordMsg = document.getElementById('password-message');

    async function checkUsername() {
        const username = usernameInput.value.trim();
        usernameMsg.textContent = '';

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
            usernameMsg.textContent = data.message || '';
        } catch (error) {
            console.error(error);
        }
    }

    async function checkEmail() {
        const email = emailInput.value.trim();
        emailMsg.textContent = '';

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
            emailMsg.textContent = data.message || '';
        } catch (error) {
            console.error(error);
        }
    }

    usernameInput.addEventListener('blur', checkUsername);
    emailInput.addEventListener('blur', checkEmail);

    initFormAjax('signup-form', (data) => {
        usernameMsg.textContent = '';
        emailMsg.textContent = '';
        passwordMsg.textContent = '';

        if (data.success) {
            alert(data.message);
            return;
        }

        if (data.errors && data.errors.username) {
            usernameMsg.textContent = data.errors.username;
        }

        if (data.errors && data.errors.email) {
            emailMsg.textContent = data.errors.email;
        }

        if (data.errors && data.errors.password) {
            passwordMsg.textContent = data.errors.password;
        }

        if (data.message) {
            console.error(data.message);
        }
    }, (error) => {
        console.error('Erreur AJAX signup :', error);
    });
});