<?php
$success = flash('success');
$error = flash('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/scss/main.css">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>
    <title><?php echo app()['config']['name']; ?></title>
</head>
<body>
    <a class="skip-link" href="#main-content">Aller au contenu principal</a>

    <?php partial('partials/header'); ?>

    <main id="main-content" class="main-content" tabindex="-1">
        <div class="container py-4">
            <?php if ($success): ?>
                <div class="alert alert-success" role="status" aria-live="polite"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert" aria-live="assertive"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </div>
        <?php require $viewPath; ?>
    </main>

    <?php partial('partials/footer'); ?>

    <script src="/js/app.js"></script>
</body>
</html>
