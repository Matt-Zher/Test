<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

// Включим показ всех ошибок (только для разработки)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

class BooksListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        // Подключаем модуль инфоблоков
        if (!Loader::includeModule('iblock')) {
            ShowError("Не удалось подключить модуль Инфоблоки");
            return;
        }

        // Проверяем параметр IBLOCK_ID
        $iblockId = (int) $this->arParams['IBLOCK_ID'];
        if ($iblockId <= 0) {
            ShowError("Не указан IBLOCK_ID");
            return;
        }

        // Подключаем классы репозитория и валидатора из local/lib
        $libPath = $_SERVER["DOCUMENT_ROOT"]."/local/lib/library";
        if (file_exists($libPath."/Repository/BookRepository.php")) {
            require_once $libPath."/Repository/BookRepository.php";
        }
        if (file_exists($libPath."/Validator/BookValidator.php")) {
            require_once $libPath."/Validator/BookValidator.php";
        }
        // Если не используете автозагрузку, можно подключить через init.php или Composer

        $validator  = new BookValidator();
        $repository = new BookRepository($iblockId);

        // Обрабатываем POST-запрос (create/update/delete)
        $this->handleRequest($validator, $repository);

        // Получаем список книг и выводим в шаблон
        $this->loadBooks($repository);

        $this->includeComponentTemplate();
    }

    /** Обработка POST-запросов */
    private function handleRequest(BookValidator $validator, BookRepository $repository): void
    {
        $request = Application::getInstance()->getContext()->getRequest();
        if (!$request->isPost() || !check_bitrix_sessid()) {
            return;
        }
        $action = $request->getPost("action");
        // Действия: create, update, delete
        if ($action === "create") {
            $this->createBook($validator, $repository, $request);
        } elseif ($action === "update") {
            $this->updateBook($validator, $repository, $request);
        } elseif ($action === "delete") {
            $this->deleteBook($repository, $request);
        }
    }

    /** Чтение списка книг */
    private function loadBooks(BookRepository $repository): void
{
    $collection = $repository->getAll();

    $books = [];

    foreach ($collection as $book) {

        $books[] = [

            'ID' => $book->getId(),

            'NAME' => $book->getName(),

            'AUTHOR' => $book->getAuthor()?->getValue(),

            'YEAR' => $book->getYear()?->getValue(),

            'DESCRIPTION' => $book->getDescription()?->getValue(),

            'ACTIVE' => $book->getActive(),

        ];
    }

    $this->arResult['BOOKS'] = $books;
}

    /** Создание книги */
    private function createBook(BookValidator $validator, BookRepository $repository, $request): void
    {
        $data = [
            "NAME"        => trim($request->getPost("NAME")),
            "AUTHOR"      => trim($request->getPost("AUTHOR")),
            "YEAR"        => (int)$request->getPost("YEAR"),
            "DESCRIPTION" => trim($request->getPost("DESCRIPTION")),
        ];
        // Валидация
        $errors = $validator->validate($data);
        if (!empty($errors)) {
            $this->arResult["ERRORS"] = $errors;
            return;
        }
        // Сохранение через репозиторий
        $newId = $repository->create($data);
        if ($newId === false) {
            $this->arResult["ERROR"] = $repository->getLastError();
        } else {
            // Успешно – редирект (Post-Redirect-Get)
            LocalRedirect($request->getRequestedPage());
        }
    }

    /** Обновление книги */
    private function updateBook(BookValidator $validator, BookRepository $repository, $request): void
    {
        $id = (int)$request->getPost("ID");
        $data = [
            "NAME"        => trim($request->getPost("NAME")),
            "AUTHOR"      => trim($request->getPost("AUTHOR")),
            "YEAR"        => (int)$request->getPost("YEAR"),
            "DESCRIPTION" => trim($request->getPost("DESCRIPTION")),
        ];
        $errors = $validator->validate($data);
        if (!empty($errors)) {
            $this->arResult["ERRORS"] = $errors;
            return;
        }
        $success = $repository->update($id, $data);
        if (!$success) {
            $this->arResult["ERROR"] = $repository->getLastError();
        } else {
            LocalRedirect($request->getRequestedPage());
        }
    }

    /** Удаление книги */
    private function deleteBook(BookRepository $repository, $request): void
    {
        $id = (int)$request->getPost("ID");
        if (!$repository->delete($id)) {
            $this->arResult["ERROR"] = $repository->getLastError();
        } else {
            LocalRedirect($request->getRequestedPage());
        }
    }
}
