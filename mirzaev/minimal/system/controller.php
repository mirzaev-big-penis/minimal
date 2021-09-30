<?php

declare(strict_types=1);

namespace mirzaev\minimal;

use mirzaev\minimal\model;

use Exception;

/**
 * Контроллер
 *
 * @package mirzaev\minimal
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class controller
{
    /**
     * Постфикс
     */
    private string $postfix = '_controller';

    /**
     * Модель
     */
    protected model $model;

    /**
     * Шаблонизатор представления
     */
    protected object $view;

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Отрисовка шаблона
     *
     * @param string $route Маршрут
     */
    public function view(string $route)
    {
        // Чтение представления по шаблону пути: "/views/[controller]/index
        // Никаких слоёв и шаблонизаторов
        // Не стал в ядре записывать путь до шаблонов
        if (file_exists($view = core::path() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $route . DIRECTORY_SEPARATOR . 'index.html')) {
            include $view;
        }
    }

    /**
     * Записать свойство
     *
     * @param string $name Название
     * @param mixed $value Значение
     */
    public function __set(string $name, mixed $value = null): void
    {
        match ($name) {
            'model' => (function () use ($value) {
                if (isset($this->model)) {
                    // Свойство уже было инициализировано

                    // Выброс исключения (неудача)
                    throw new exception('Запрещено реинициализировать модель ($this->model)', 500);
                } else {
                    // Свойство ещё не было инициализировано

                    if ($value instanceof model) {
                        // Передано подходящее значение

                        // Запись свойства (успех)
                        $this->model = $value;
                    } else {
                        // Передано неподходящее значение

                        // Выброс исключения (неудача)
                        throw new exception('Модель ($this->model) должна хранить инстанцию "mirzaev\minimal\model"', 500);
                    }
                }
            })(),
            'view' => (function () use ($value) {
                if (isset($this->view)) {
                    // Свойство уже было инициализировано

                    // Выброс исключения (неудача)
                    throw new exception('Запрещено реинициализировать шаблонизатор представления ($this->view)', 500);
                } else {
                    // Свойство ещё не было инициализировано

                    if (is_object($value)) {
                        // Передано подходящее значение

                        // Запись свойства (успех)
                        $this->view = $value;
                    } else {
                        // Передано неподходящее значение

                        // Выброс исключения (неудача)
                        throw new exception('Шаблонизатор представлений ($this->view) должен хранить объект', 500);
                    }
                }
            })(),
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
            'postfix' => (function () {
                if (isset($this->postfix)) {
                    // Свойство уже было инициализировано
                } else {
                    // Свойство ещё не было инициализировано

                    // Инициализация со значением по умолчанию
                    $this->__set('postfix', '_controller');
                }

                // Возврат (успех)
                return $this->postfix;
            })(),
            'view' => $this->view ?? throw new exception("Свойство \"\$$name\" не инициализировано", 500),
            'model' => $this->model ?? throw new exception("Свойство \"\$$name\" не инициализировано", 500),
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
