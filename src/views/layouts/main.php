<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    
    
    <!-- CSS global -->
    <link rel="stylesheet" href="./views/style.css">
</head>
<body>
    <div class="container">
        <?= $content ?>
    </div>
    <script src="/js/app.js"></script>
    <script src="/js/message.js"></script>
</body>
</html>
