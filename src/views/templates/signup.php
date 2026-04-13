<form id="signup-form" action="/?action=signup-register" method="post">
    <input type="text" name="username" id="username" placeholder="Pseudo">
    <small id="username-message"></small>

    <input type="email" name="email" id="email" placeholder="Email">
    <small id="email-message"></small>

    <input type="password" name="password" id="password" placeholder="Mot de passe">
    <small id="password-message"></small>

    <button type="submit">S'inscrire</button>
</form>

<p><a href="/?action=login">Déjà un compte ? Se connecter</a></p>

<script src="/js/common/app.js"></script>
<script src="/js/signup.js"></script>