<h1>/login</h1>

<div class="term">
    <div class="term-bar"></span><span class="term-title">~/login</span></div>
    <form class="term-body" method="post" action="/login">
        <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
        <div class="line"><span class="prompt">username:</span><input name="username" autocomplete="username" required autofocus></div>
        <div class="line"><span class="prompt">password:</span><input name="password" type="password" autocomplete="current-password" required></div>
        <button type="submit">login</button>
    </form>
</div>
<p class="hint">no account yet? <a href="/register">/register</a></p>
