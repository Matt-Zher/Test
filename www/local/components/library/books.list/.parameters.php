<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "IBLOCK_ID" => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("BOOKS_LIST_IBLOCK_ID"),
            "TYPE"    => "STRING",      // или "LIST" с выбором инфоблока (с использованием REFRESH)
            "DEFAULT" => "",
        ],
        "CACHE_TIME" => ["DEFAULT" => 3600],
    ]
];
