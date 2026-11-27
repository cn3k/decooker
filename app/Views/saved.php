<h1>/saved</h1>

<?php if (!$entries): ?>
    <p class="hint">nothing saved yet. decook something on <a href="/tools">/tools</a> and hit "save it"</p>
<?php else: ?>
    <div class="term">
        <div class="term-bar"></span><span class="term-title">~/saved</span></div>
        <div class="term-body">
            <table>
                <tr><th>when</th><th>what</th><th>input</th><th>output</th><th></th></tr>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?= e($entry['created_at']) ?></td>
                        <td><?= e($entry['action']) ?><br><span class="hint"><?= e(Codec::label($entry['format'])) ?></span></td>
                        <td class="mono" title="<?= e($entry['input']) ?>"><?= e(short($entry['input'])) ?></td>
                        <td class="mono" title="<?= e($entry['output']) ?>"><?= e(short($entry['output'])) ?></td>
                        <td><button type="button" class="danger" data-delete="<?= (int) $entry['id'] ?>">delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <dialog id="confirm">
        <form method="post" action="/saved/delete" class="modal-body">
            <p class="modal-title">are you super-duper sure?</p>
            <p class="hint">this one will be gone forever 0___0</p>
            <input type="hidden" name="id" id="confirm-id" value="">
            <div class="row">
                <button type="button" class="ghost" id="confirm-no">nope, keep it</button>
                <button type="submit" class="danger">yes, delete it</button>
            </div>
        </form>
    </dialog>

    <script>
        (function () {
            var dialog = document.getElementById('confirm');
            document.querySelectorAll('[data-delete]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('confirm-id').value = btn.dataset.delete;
                    dialog.showModal();
                });
            });
            document.getElementById('confirm-no').addEventListener('click', function () { dialog.close(); });
            dialog.addEventListener('click', function (e) { if (e.target === dialog) dialog.close(); });
        })();
    </script>
<?php endif; ?>
