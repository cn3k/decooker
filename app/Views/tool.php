<h1>encode/decode</h1>
<p class="hint">paste a cooked string and let deCooker sort it out</p>

<div class="term">
    <div class="term-bar"><span class="term-title">~/tools</span></div>
    <form class="term-body" method="post" action="/tools">
        <div class="line">
            <span class="prompt"><span class="username"><?= e($promptUser) ?></span>@<span class="hostname">deCooker</span> $&gt;</span>
            <textarea name="input" rows="10" spellcheck="false" autofocus placeholder="paste your cooked string here..."><?= e($input) ?></textarea>
        </div>
        <div class="row">
            <select name="action">
                <option value="encode" <?= $action === 'encode' ? 'selected' : '' ?>>encode</option>
                <option value="decode" <?= $action === 'decode' ? 'selected' : '' ?>>decode</option>
                <option value="find" <?= $action === 'find' ? 'selected' : '' ?>>find (auto-detect, login needed)</option>
            </select>
            <select name="format">
                <?php foreach ($formats as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= $format === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">run</button>
        </div>
    </form>
</div>

<?php if ($error): ?>
    <p class="error"><?= e($error) ?></p>
<?php endif; ?>

<?php if ($result): ?>
    <div class="term">
        <div class="term-bar"><span class="term-title">~/output</span></div>
        <div class="term-body">
            <?php if ($result['action'] === 'find'): ?>
                <p class="detected">detected: <strong><?= e($formats[$result['format']]) ?></strong><p>
            <?php endif; ?>
            <div class="line">
                <span class="prompt"><span class="username"><?= e($promptUser) ?></span>@<span class="hostname">deCooker</span> $&gt;</span>
                <textarea readonly rows="5" spellcheck="false"><?= e($result['output']) ?></textarea>
            </div>

            <?php if ($loggedIn): ?>
                <form method="post" action="/saved">
                    <input type="hidden" name="action" value="<?= e($result['action']) ?>">
                    <input type="hidden" name="format" value="<?= e($result['format']) ?>">
                    <input type="hidden" name="input" value="<?= e($input) ?>">
                    <input type="hidden" name="output" value="<?= e($result['output']) ?>">
                    <button type="submit">save it</button>
                </form>
            <?php else: ?>
                <p class="hint"><a href="/login">/login</a> to save your results :)</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
