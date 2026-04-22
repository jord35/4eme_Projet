<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php $globalUnreadMessageCount = (int) ($globalUnreadMessageCount ?? 0); ?>

    <div class="site-shell">
        <?php require __DIR__ . '/../common/nav.php'; ?>

        <main class="site-main">
            <div class="site-frame">
                <?= $content ?>
            </div>
        </main>

        <?php require __DIR__ . '/../common/footer.php'; ?>
    </div>

    <script src="/js/common/app.js"></script>
</body>
</html>