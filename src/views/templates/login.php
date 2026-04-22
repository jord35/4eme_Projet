<section class="auth-page auth-page--login">
    <div class="auth-page__inner site-frame">
        <div class="auth-page__content">
            <div class="auth-page__panel">
                <h1 class="auth-page__title">Connexion</h1>

                <form
                    id="login-form"
                    class="auth-form form-shell"
                    method="post"
                    action="/?action=login"
                    novalidate>

                    <div class="auth-form__field form-field">
                        <label class="auth-form__label" for="email">Adresse email</label>
                        <input
                            class="auth-form__input"
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Votre adresse email"
                            autocomplete="email"
                            required
                            aria-describedby="login-message">
                    </div>

                    <div class="auth-form__field form-field">
                        <label class="auth-form__label" for="password">Mot de passe</label>
                        <input
                            class="auth-form__input"
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Votre mot de passe"
                            autocomplete="current-password"
                            required
                            aria-describedby="login-message">
                    </div>

                    <button class="auth-form__submit button button--primary" type="submit">Se connecter</button>
                </form>

                <p
                    id="login-message"
                    class="auth-page__message form-message"
                    data-state=""
                    role="status"
                    aria-live="polite"
                    aria-atomic="true"
                    hidden></p>

                <p class="auth-page__secondary">
                    <a class="auth-page__secondary-link" href="/?action=signup">
                        Pas de compte ? Inscrivez-vous
                    </a>
                </p>
            </div>
        </div>

        <div class="auth-page__media">
            <img
                class="auth-page__image"
                src="/assets/welkom-image.webp"
                alt="">
        </div>
    </div>
</section>

<script src="/js/login.js" defer></script>