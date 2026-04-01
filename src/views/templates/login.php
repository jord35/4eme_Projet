<form id="login-form" method="POST" action="index.php?action=authenticate">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

<p id="login-message"></p>

<script src="js/app.js" defer></script>
<script src="js/login.js" defer></script>