<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentDescription = [
    "NAME"        => Loc::getMessage("BOOKS_LIST_NAME"),
    "DESCRIPTION" => Loc::getMessage("BOOKS_LIST_DESC"),
    // Определяем место в виртуальном дереве компонентов:
    "PATH" => ["ID" => "library", "NAME" => "Библиотека"],
    "CACHE_PATH"  => "Y", // разрешаем очистку кеша в админке сайта
    "COMPLEX"     => "N",
];
