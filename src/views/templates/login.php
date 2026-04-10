
<form id="login-form" method="POST" action="/?action=login">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

<p id="login-message"></p>
<a href="/?action=my-account">Mon compte</a>

<script src="js/common/app.js" defer></script>
<script src="js/login.js" defer></script>