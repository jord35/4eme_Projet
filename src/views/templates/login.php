<section class="login-section">
    <div class="login-content">
        <h1>Connexion</h1>

        <form id="login-form" method="post" action="/?action=login" novalidate>
            <div class="login-field">
                <label for="email">Adresse email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Votre adresse email"
                    autocomplete="username"
                    required
                    aria-describedby="login-message">
            </div>

            <div class="login-field">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Votre mot de passe"
                    autocomplete="current-password"
                    required
                    aria-describedby="login-message">
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <p id="login-message"></p>

        <p>
            <a href="/?action=signup">Pas de compte ? Inscrivez-vous</a>
        </p>
    </div>

    <div class="login-media">
        <img src="/assets/welkom-image.webp" alt="">
    </div>
</section>

<script src="/js/common/app.js" defer></script>
<script src="/js/login.js" defer></script>