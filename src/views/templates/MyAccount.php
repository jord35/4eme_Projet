<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
</head>
<body>

    <h1>Mon compte</h1>

    <section>
        <div>
            <p><strong>Image de profil</strong></p>

            <img
                id="profile-image-preview"
                src=""
                alt="Image de profil"
                style="max-width: 200px; display: block;"
            >

            <p id="no-profile-image">Pas d’image</p>
        </div>

        <div>
            <p><strong>Pseudo :</strong> Ilia</p>
            <p><strong>Membre depuis :</strong> x temps</p>
            <p><strong>Nombre de livres :</strong> 0</p>
        </div>
    </section>

    <hr>

    <form
        id="my-account-form"
        method="POST"
        action="index.php?action=updateMyAccount"
        enctype="multipart/form-data"
    >
        <div>
            <label for="username">Pseudo</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Votre pseudo"
            >
        </div>

        <div>
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Votre email"
            >
        </div>

        <div>
            <label for="password">Nouveau mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Nouveau mot de passe"
            >
        </div>

        <div>
            <label for="profile_image">Image de profil</label>
            <input
                type="file"
                id="profile_image"
                name="profile_image"
                accept="image/*"
            >
        </div>

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <p id="my-account-message"></p>

    <script src="js/common/app.js"></script>
    <script src="js/myaccount.js"></script>
</body>
</html>