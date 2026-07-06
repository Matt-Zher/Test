<?php

class BookValidator
{
    /** Валидирует данные книги, возвращает массив ошибок */
    public function validate(array $data): array
    {
        $errors = [];

        // Название книги обязательно и не слишком длинное
        if (empty($data['NAME'])) {
            $errors[] = "Поле «Название» обязательно.";
        } elseif (mb_strlen($data['NAME']) > 255) {
            $errors[] = "Название не может превышать 255 символов.";
        }

        // Автор обязателен
        if (empty($data['AUTHOR'])) {
            $errors[] = "Поле «Автор» обязательно.";
        }

        // Год – положительное целое (если задан)
        if (!empty($data['YEAR']) && (!is_numeric($data['YEAR']) || (int)$data['YEAR'] <= 0)) {
            $errors[] = "Поле «Год» должно быть положительным числом.";
        }

        return $errors;
    }
}
