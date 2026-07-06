<?php

use Bitrix\Iblock\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Objectify\EntityObject;
use Bitrix\Iblock\Elements\EO_ElementBooks_Collection;
use Bitrix\Iblock\Elements\EO_ElementBooks;

class BookRepository
{
    private string $entityClass;

    public function __construct(int $iblockId)
    {
        if (!Loader::includeModule('iblock')) {
            throw new RuntimeException('Не удалось подключить модуль iblock');
        }

        $this->entityClass = Iblock::wakeUp($iblockId)->getEntityDataClass();
    }

    /**
     * Список книг
     */
    public function getAll(
        array $filter = [],
        array $order = ['ID' => 'ASC']
    ): EO_ElementBooks_Collection {
        return $this->entityClass::getList([
            'select' => [
                'ID',
                'NAME',
                'ACTIVE',
                'AUTHOR',
                'YEAR',
                'DESCRIPTION',
            ],
            'filter' => $filter,
            'order' => $order,
        ])->fetchCollection();
    }

    /**
     * Получить книгу
     */
    public function getById(int $id): ?EO_ElementBooks
    {
        return $this->entityClass::getByPrimary($id, [
            'select' => [
                'ID',
                'NAME',
                'ACTIVE',
                'AUTHOR',
                'YEAR',
                'DESCRIPTION',
            ]
        ])->fetchObject();
    }

    /**
     * Создать книгу
     */
    public function create(array $fields): int
    {
        $book = $this->entityClass::createObject();

        $book->setName($fields['NAME']);
        $book->setActive($fields['ACTIVE'] ?? 'Y');

        if (isset($fields['AUTHOR'])) {
            $book->getAuthor()->setValue($fields['AUTHOR']);
        }

        if (isset($fields['YEAR'])) {
            $book->getYear()->setValue($fields['YEAR']);
        }

        if (isset($fields['DESCRIPTION'])) {
            $book->getDescription()->setValue($fields['DESCRIPTION']);
        }

        $result = $book->save();

        if (!$result->isSuccess()) {
            throw new RuntimeException(
                implode(PHP_EOL, $result->getErrorMessages())
            );
        }

        return $result->getId();
    }

    /**
     * Обновить книгу
     */
    public function update(int $id, array $fields): bool
    {
        $book = $this->getById($id);

        if (!$book) {
            throw new RuntimeException("Книга {$id} не найдена");
        }

        if (isset($fields['NAME'])) {
            $book->setName($fields['NAME']);
        }

        if (isset($fields['ACTIVE'])) {
            $book->setActive($fields['ACTIVE']);
        }

        if (isset($fields['AUTHOR'])) {
            $book->getAuthor()->setValue($fields['AUTHOR']);
        }

        if (isset($fields['YEAR'])) {
            $book->getYear()->setValue($fields['YEAR']);
        }

        if (isset($fields['DESCRIPTION'])) {
            $book->getDescription()->setValue($fields['DESCRIPTION']);
        }

        $result = $book->save();

        if (!$result->isSuccess()) {
            throw new RuntimeException(
                implode(PHP_EOL, $result->getErrorMessages())
            );
        }

        return true;
    }

    /**
     * Удалить книгу
     */
    public function delete(int $id): bool
    {
        $result = $this->entityClass::delete($id);

        if (!$result->isSuccess()) {
            throw new RuntimeException(
                implode(PHP_EOL, $result->getErrorMessages())
            );
        }

        return true;
    }
}