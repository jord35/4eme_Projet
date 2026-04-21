<section class="signup-section">
    <div class="signup-content">
        <h1>Inscription</h1>

        <form id="signup-form" action="/?action=signup-register" method="post" novalidate>
            <div class="signup-field">
                <label for="username">Pseudo</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    placeholder="Votre pseudo"
                    autocomplete="username"
                    required
                    aria-describedby="username-message">
                <small id="username-message"></small>
            </div>

            <div class="signup-field">
                <label for="email">Adresse email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Votre adresse email"
                    autocomplete="email"
                    required
                    aria-describedby="email-message">
                <small id="email-message"></small>
            </div>

            <div class="signup-field">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Votre mot de passe"
                    autocomplete="new-password"
                    required
                    aria-describedby="password-message">
                <small id="password-message"></small>
            </div>

            <button type="submit">S'inscrire</button>
        </form>

        <p>
            <a href="/?action=login">Déjà inscrit ? Connectez-vous</a>
        </p>
    </div>

    <div class="signup-media">
        <img src="/assets/welkom-image.webp" alt="">
    </div>
</section>

<script src="/js/signup.js"></script>