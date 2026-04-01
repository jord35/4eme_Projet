<?php
/** @var ?string $message */
/** @var ?string $error */
/** @var ?array $picturePackage */
/** @var ?string $uploadedTitle */
/** @var ?string $uploadedComment */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Picture</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        form {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .field {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 16px;
            cursor: pointer;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .preview {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }

        .preview img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <h1>Test upload picture</h1>

    <?php if (!empty($message)): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="?action=test-picture" method="POST" enctype="multipart/form-data">
        <div class="field">
            abel for="picture">Image</label>
            <input type="file" id="picture" name="picture" accept="image/*" required>
        </div>

        <div class="field">
            abel for="title">Titre</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="field">
            abel for="comment">Commentaire</label>
            <textarea id="comment" name="comment" rows="4"></textarea>
        </div>

        <button type="submit">Envoyer</button>
    </form>

    <div class="preview">
        <h2>Résultat</h2>

        <?php if (!empty($picturePackage)): ?>
            <img
                src="<?= htmlspecialchars($picturePackage['src']) ?>"
                srcset="<?= htmlspecialchars($picturePackage['srcset']) ?>"
                sizes="<?= htmlspecialchars($picturePackage['sizes']) ?>"
                alt="<?= htmlspecialchars($picturePackage['alt'] ?? '') ?>"
                width="200"
                height="200"
            >

            <p><strong>Titre :</strong> <?= htmlspecialchars($uploadedTitle ?? '') ?></p>
            <p><strong>Commentaire :</strong> <?= htmlspecialchars($uploadedComment ?? '') ?></p>
            <p><strong>Alt :</strong> <?= htmlspecialchars($picturePackage['alt'] ?? '') ?></p>
        <?php else: ?>
            <p>Aucune image affichée pour le moment.</p>
        <?php endif; ?>
    </div>

</body>
</html>