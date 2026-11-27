<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>deCooker<?= $title !== '' ? ' ~ ' . e($title) : '' ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<nav>
    <a class="brand" href="/tools"><span class="muted">$</span><span class="hostname"> deCooker</span></a>
    <a href="/tools">/tools</a>
    <?php if ($loggedIn): ?>
        <a href="/saved">/saved</a>
        <span class="muted">~<?= e($username) ?></span>
        <a href="/logout">/logout</a>
    <?php else: ?>
        <a href="/login">/login</a>
        <a href="/register">/register</a>
    <?php endif; ?>
</nav>
<main>
    <?php if ($flash): ?><p class="flash"><?= e($flash) ?></p><?php endif; ?>
    <?= $content ?>
</main>
</body>
</html>
