<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs($templateFolder . '/script.js');
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
<form method="get">
    <h3>Поиск</h3>
    <div>
        <label>Название:<br>
            <input type="text" name="NAME" id="book-name-filter">
        </label>
    </div>
    <div>
        <label>Автор:<br>
            <input type="text" name="AUTHOR" id="book-author-filter">
        </label>
    </div>
    <div>
        <label>Год:<br>
            <input type="number" name="YEAR" id="book-year-filter">
        </label>
    </div>
    <button type="submit" id="filter-btn" name="action" value="filter">
        Найти
    </button>
<button type="button" id="cancel-filter">
    Отмена
</button>
</form>

<form method="post">
    <?=bitrix_sessid_post()?>
    <h3>Создание/Обновление</h3>
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
    </table>
    <?php if ($arResult['PAGINATION']['pages'] > 1): ?>

    <div class="pagination">

        <?php if ($arResult['PAGINATION']['page'] > 1): ?>

            <a href="<?= $APPLICATION->GetCurPageParam(
                'page=' . ($arResult['PAGINATION']['page'] - 1),
                ['page']
            ) ?>">
                ← Назад
            </a>

        <?php endif; ?>

        <?php for ($i = 1; $i <= $arResult['PAGINATION']['pages']; $i++): ?>

            <?php if ($i == $arResult['PAGINATION']['page']): ?>

                <strong><?= $i ?></strong>

            <?php else: ?>

                <a href="<?= $APPLICATION->GetCurPageParam(
                    'page=' . $i,
                    ['page']
                ) ?>">
                    <?= $i ?>
                </a>

            <?php endif; ?>

        <?php endfor; ?>

        <?php if ($arResult['PAGINATION']['page'] < $arResult['PAGINATION']['pages']): ?>

            <a href="<?= $APPLICATION->GetCurPageParam(
                'page=' . ($arResult['PAGINATION']['page'] + 1),
                ['page']
            ) ?>">
                Вперед →
            </a>

        <?php endif; ?>

    </div>

<?php endif; ?>

