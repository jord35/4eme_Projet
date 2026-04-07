<<<<<<< HEAD
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>

    <form id="login-form" method="POST" action="index.php?action=authenticate">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="" required>
        <button type="submit">Se connecter</button>
    </form>

    <p id="login-message"></p>
    <a href="index.php?action=myAccount">Mon compte</a>

    <script src="js/common/app.js"></script>
    <script src="js/login.js"></script>
</body>
</html>
=======
<form id="login-form" method="POST" action="index.php?action=authenticate">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

<p id="login-message"></p>

<script src="js/app.js" defer></script>
<script src="js/login.js" defer></script>
>>>>>>> develop
