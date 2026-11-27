<h1>/register</h1>

<div class="term">
    <div class="term-bar"><span class="term-title">~/register</span></div>
    <form class="term-body" method="post" action="/register">
        <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
        <div class="line"><span class="prompt">username:</span><input name="username" placeholder="3-32 chars, letters/numbers/_" autocomplete="username" required autofocus></div>
        <div class="line"><span class="prompt">password:</span><input name="password" type="password" placeholder="min 8 chars" autocomplete="new-password" required></div>
        <button type="submit">create account</button>
    </form>
</div>
<p class="hint">already cooking here? <a href="/login">/login</a></p>
