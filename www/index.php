<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Книги");
?>

<?$APPLICATION->IncludeComponent(
    "library:books.list",
    "",
    [
        "IBLOCK_ID" => 5,
    ]
);
?>

</p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>