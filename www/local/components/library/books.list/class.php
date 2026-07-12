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
        $this->loadBooks($repository);
        // Обрабатываем POST-запрос (create/update/delete)
        $this->handleRequestPost($validator, $repository);
        $this->handleRequestGet($validator, $repository);
        // Получаем список книг и выводим в шаблон
        
        $this->includeComponentTemplate();
    }

    /** Обработка POST-запросов */
    private function handleRequestPost(BookValidator $validator, BookRepository $repository): void
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
    private function handleRequestGet(BookValidator $validator, BookRepository $repository): void 
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $action = $request->getQuery("action");
        if ($action === "filter") {
            $this->filterBooks($repository, $request);
        }
    }
    /** Чтение списка книг */
    private function loadBooks(BookRepository $repository): void
    {   
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = max(1, (int)($_GET['limit'] ?? 10));
        $result = $repository->getAll($page, $limit);
        $books = [];
        foreach ($result['items'] as $book) {

            $books[] = [

                'ID' => $book->getId(),

                'NAME' => $book->getName(),

                'AUTHOR' => $book->getAuthor()?->getValue(),

                'YEAR' => $book->getYear()?->getValue(),

                'DESCRIPTION' => $book->getDescription()?->getValue(),

                'ACTIVE' => $book->getActive(),

            ];
        }

        $this->arResult = [
            'BOOKS' => $books,
            'PAGINATION' => $result['pagination'],
        ];
    }

    /** Чтение списка книг (с фильтром) */
    private function filterBooks(BookRepository $repository, $request): void
    {   
        $filterData = [
            "NAME"   => trim($request->getQuery("NAME")),
            "AUTHOR" => trim($request->getQuery("AUTHOR")),
            "YEAR"   => (int)$request->getQuery("YEAR"),
        ];
        $filter = [];
        if ($filterData['NAME'] !== '') {
            $filter['%NAME'] = $filterData['NAME'];
        }
        if ($filterData['AUTHOR'] !== '') {
            $filter['%AUTHOR.VALUE'] = $filterData['AUTHOR'];
        }
        if ($filterData['YEAR'] > 0) {
            $filter['=YEAR.VALUE'] = $filterData['YEAR'];
        }
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = max(1, (int)($_GET['limit'] ?? 10));
        $result = $repository->getAll($page, $limit, $filter);
        $books = [];
        foreach ($result['items'] as $book) {

            $books[] = [

                'ID' => $book->getId(),

                'NAME' => $book->getName(),

                'AUTHOR' => $book->getAuthor()?->getValue(),

                'YEAR' => $book->getYear()?->getValue(),

                'DESCRIPTION' => $book->getDescription()?->getValue(),

                'ACTIVE' => $book->getActive(),

            ];
        }

        $this->arResult = [
            'BOOKS' => $books,
            'PAGINATION' => $result['pagination'],
        ];
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
