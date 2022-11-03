<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Встроенные библиотеки
use exception;

/**
 * Модель
 *
 * @package mirzaev\minimal
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class model
{
    /**
     * Постфикс
     */
    private string $postfix = '_model';

    /**
     * Записать свойство
     *
     * @param string $name Название
     * @param mixed $value Значение
     */
    public function __set(string $name, mixed $value = null): void
    {
        match ($name) {
            'postfix' => (function () use ($value) {
                if (isset($this->postfix)) {
                    // Свойство уже было инициализировано

                    // Выброс исключения (неудача)
                    throw new exception('Запрещено реинициализировать постфикс ($this->postfix)', 500);
                } else {
                    // Свойство ещё не было инициализировано

                    if ($value = filter_var($value, FILTER_SANITIZE_STRING)) {
                        // Передано подходящее значение

                        // Запись свойства (успех)
                        $this->postfix = $value;
                    } else {
                        // Передано неподходящее значение

                        // Выброс исключения (неудача)
                        throw new exception('Постфикс ($this->postfix) должен быть строкой', 500);
                    }
                }
            })(),
            default => throw new exception("Свойство \"\$$name\" не найдено", 404)
        };
    }

    /**
     * Прочитать свойство
     *
     * Записывает значение по умолчанию, если свойство не инициализировано
     *
     * @param string $name Название
     *
     * @return mixed Содержимое
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'postfix' => (function() {
                if ($this->__isset('postfix')) {
                    // Свойство уже было инициализировано
                } else {
                    // Свойство ещё не было инициализировано

                    // Инициализация со значением по умолчанию
                    $this->__set('postfix', '_model');
                }

                // Возврат (успех)
                return $this->postfix;
            })(),
            default => throw new exception("Свойство \"\$$name\" не обнаружено", 404)
        };
    }

    /**
     * Проверить свойство на инициализированность
     *
     * @param string $name Название
     */
    public function __isset(string $name): bool
    {
        return match ($name) {
            default => isset($this->{$name})
        };
    }

    /**
     * Удалить свойство
     *
     * @param string $name Название
     */
    public function __unset(string $name): void
    {
        match ($name) {
            default => (function () use ($name) {
                // Удаление
                unset($this->{$name});
            })()
        };
    }
}
