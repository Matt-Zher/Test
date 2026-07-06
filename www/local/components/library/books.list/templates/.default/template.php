<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<!-- Вывод ошибок компонента -->
<?php if (!empty($arResult["ERROR"])): ?>
    <div class="error"><?=htmlspecialchars($arResult["ERROR"])?></div>
<?php endif; ?>
<?php if (!empty($arResult["ERRORS"])): ?>
    <?php foreach ($arResult["ERRORS"] as $err): ?>
        <div class="error"><?=htmlspecialchars($err)?></div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Форма создания книги -->
<form method="post">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="ID" id="book-id" value="">
    <div>
        <label>Название:<br>
            <input type="text" name="NAME" id="book-name">
        </label>
    </div>
    <div>
        <label>Автор:<br>
            <input type="text" name="AUTHOR" id="book-author">
        </label>
    </div>
    <div>
        <label>Год:<br>
            <input type="number" name="YEAR" id="book-year">
        </label>
    </div>
    <div>
        <label>Описание:<br>
            <textarea name="DESCRIPTION" id="book-description"></textarea>
        </label>
    </div>
    <button type="submit" id="submit-btn" name="action" value="create">
        Добавить книгу
    </button>
<button type="button" id="cancel-edit">
    Отмена
</button>
<script>
    document.getElementById('cancel-edit').addEventListener('click', function () {
    document.querySelector('form').reset();
    document.getElementById('book-id').value = '';
    var button = document.getElementById('submit-btn');
    document.getElementById('book-id').value = '';
    document.getElementById('book-name').value = '';
    document.getElementById('book-author').value = '';
    document.getElementById('book-year').value = '';
    document.getElementById('book-description').value = '';
    button.value = 'create';
    button.textContent = 'Добавить книгу';});
</script>
</form>

<!-- Таблица со списком книг -->
<table border="1" cellpadding="5" cellspacing="0" style="margin-top:20px;">
    <tr>
        <th>ID</th><th>Название</th><th>Автор</th><th>Год</th><th>Действия</th>
    </tr>
    <?php foreach ($arResult["BOOKS"] as $book): ?>
        <tr
            class="book-row"
            data-id="<?= $book['ID'] ?>"
            data-name="<?= htmlspecialchars($book['NAME']) ?>"
            data-author="<?= htmlspecialchars($book['AUTHOR']) ?>"
            data-year="<?= htmlspecialchars($book['YEAR']) ?>"
            data-description="<?= htmlspecialchars($book['DESCRIPTION']) ?>"
        >
            <td><?=htmlspecialchars($book["ID"])?></td>
            <td><?=htmlspecialchars($book["NAME"])?></td>
            <td><?=htmlspecialchars($book["AUTHOR"] ?? "")?></td>
            <td><?=htmlspecialchars($book["YEAR"] ?? "")?></td>
            <td>
                <!-- Кнопка Удалить -->
                <form method="post" style="display:inline" onsubmit="return confirm('Удалить книгу?');">
                    <?=bitrix_sessid_post()?>
                    <input type="hidden" name="ID" value="<?=htmlspecialchars($book["ID"])?>">
                    <button name="action" value="delete">Удалить</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <script>
        document.querySelectorAll('.book-row').forEach(function(row) {

            row.addEventListener('click', function () {

            document.getElementById('book-id').value = this.dataset.id;

            document.getElementById('book-name').value = this.dataset.name;

            document.getElementById('book-author').value = this.dataset.author;

            document.getElementById('book-year').value = this.dataset.year;

            document.getElementById('book-description').value = this.dataset.description;

            var button = document.getElementById('submit-btn');

            button.value = 'update';
            button.textContent = 'Сохранить изменения';
        });
        });
</script>
</table>
