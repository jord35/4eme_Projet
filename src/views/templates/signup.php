<section class="auth-page auth-page--signup">
    <div class="auth-page__inner site-frame">
        <div class="auth-page__content">
            <div class="auth-page__panel">
                <h1 class="auth-page__title">Inscription</h1>

                <form id="signup-form" class="auth-form form-shell" action="/?action=signup-register" method="post" novalidate>
                    <div class="auth-form__field form-field">
                        <label class="auth-form__label" for="username">Pseudo</label>
                        <input
                            class="auth-form__input"
                            type="text"
                            name="username"
                            id="username"
                            placeholder="Votre pseudo"
                            autocomplete="username"
                            required
                            aria-describedby="username-message signup-message">
                        <small id="username-message" class="form-field-message" data-state="" hidden></small>
                    </div>

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
                            aria-describedby="email-message signup-message">
                        <small id="email-message" class="form-field-message" data-state="" hidden></small>
                    </div>

                    <div class="auth-form__field form-field">
                        <label class="auth-form__label" for="password">Mot de passe</label>
                        <input
                            class="auth-form__input"
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Votre mot de passe"
                            autocomplete="new-password"
                            required
                            aria-describedby="password-message signup-message">
                        <small id="password-message" class="form-field-message" data-state="" hidden></small>
                    </div>

                    <button class="auth-form__submit button button--primary" type="submit">S'inscrire</button>
                </form>

                <p
                    id="signup-message"
                    class="auth-page__message form-message"
                    data-state=""
                    role="status"
                    aria-live="polite"
                    aria-atomic="true"
                    hidden></p>

                <p class="auth-page__secondary">
                    <a class="auth-page__secondary-link" href="/?action=login">Déjà inscrit ? Connectez-vous</a>
                </p>
            </div>
        </div>

        <div class="auth-page__media">
            <img class="auth-page__image" src="/assets/welkom-image.webp" alt="">
        </div>
    </div>
</section>

<script src="/js/signup.js" defer></script>