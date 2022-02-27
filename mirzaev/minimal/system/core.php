<?php

declare(strict_types=1);

namespace mirzaev\minimal;

use mirzaev\minimal\router;
use mirzaev\minimal\controller;
use mirzaev\minimal\model;

use exception;

/**
 * Ядро
 *
 * @package mirzaev\minimal
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 *
 * @todo
 * 1. Добавить __isset() и __unset()
 */
final class core
{
    /**
     * Соединение с базой данных
     */
    private object $storage;

    /**
     * Маршрутизатор
     */
    private router $router;

    /**
     * Контроллер
     */
    private controller $controller;

    /**
     * Модель
     */
    private model $model;

    /**
     * Пространство имён проекта
     *
     * Используется для поиска файлов по спецификации PSR-4
     */
    private string $namespace;

    /**
     * Конструктор
     *
     * @param object $storage Хранилище
     * @param router $router Маршрутизатор
     * @param string $uri Маршрут
     */
    public function __construct(object $storage = null, router $router = null, controller $controller = null, model $model = null, string $namespace = null)
    {
        if (isset($storage)) {
            // Переданы данные для хранилища

            // Проверка и запись
            $this->__set('storage', $storage);
        }

        if (isset($router)) {
            // Переданы данные для маршрутизатора

            // Проверка и запись
            $this->__set('router', $router);
        }

        if (isset($controller)) {
            // Переданы данные для контроллера

            // Проверка и запись
            $this->__set('controller', $controller);
        }

        if (isset($model)) {
            // Переданы данные для модели

            // Проверка и запись
            $this->__set('model', $model);
        }

        if (isset($namespace)) {
            // Переданы данные для пространства имён

            // Проверка и запись
            $this->__set('namespace', $namespace);
        }
    }

    /**
     * Деструктор
     *
     */
    public function __destruct()
    {
    }

    public function start(string $uri = null): ?string
    {
        // Обработка запроса
        return $this->__get('router')->handle($uri, core: $this);
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
            'storage', 'db', 'database' => (function () use ($value) {
                if ($this->__isset('storage')) {
                    // Свойство уже было инициализировано

                    // Выброс исключения (неудача)
                    throw new exception('Запрещено реинициализировать хранилище ($this->storage)', 500);
                } else {
                    // Свойство ещё не было инициализировано

                    if (is_object($value)) {
                        // Передано подходящее значение

                        // Запись свойства (успех)
                        $this->storage = $value;
                    } else {
                        // Передано неподходящее значение

                        // Выброс исключения (неудача)
                        throw new exception('Хранилище ($this->storage) должно хранить объект', 500);
                    }
                }
            })(),
            'router' => (function () use ($value) {
                if ($this->__isset('router')) {
                    // Свойство уже было инициализировано

                    // Выброс исключения (неудача)
                    throw new exception('Запрещено реинициализировать маршрутизатор ($this->router)', 500);
                } else {
                    // Свойство ещё не было инициализировано

                    if ($value instanceof router) {
                        // Передано подходящее значение

                        // Запись свойства (успех)
                        $this->router = $value;
                    } else {
                        // Передано неподходящее значение

                        // Выброс исключения (неудача)
                        throw new exception('Маршрутизатор ($this->router) должен хранить инстанцию "mirzaev\minimal\router"', 500);
                    }
                }
            })(),
            'controller' => (function () use ($value) {
                if ($this->__isset('controller')) {
                    // Свойство уже было инициализировано

                    // Выброс исключения (неудача)
                    throw new exception('Запрещено реинициализировать контроллер ($this->controller)', 500);
                } else {
                    // Свойство ещё не было инициализировано

                    if ($value instanceof controller) {
                        // Передано подходящее значение

                        // Запись свойства (успех)
                        $this->controller = $value;
                    } else {
                        // Передано неподходящее значение

                        // Выброс исключения (неудача)
                        throw new exception('Контроллер ($this->controller) должен хранить инстанцию "mirzaev\minimal\controller"', 500);
                    }
                }
            })(),
            'model' => (function () use ($value) {
                if ($this->__isset('model')) {
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
                        throw new exception('Модель ($this->model) должен хранить инстанцию "mirzaev\minimal\model"', 500);
                    }
                }
            })(),
            'namespace' => (function () use ($value) {
                if ($this->__isset('namespace')) {
                    // Свойство уже было инициализировано

                    // Выброс исключения (неудача)
                    throw new exception('Запрещено реинициализировать пространство имён ($this->namespace)', 500);
                } else {
                    // Свойство ещё не было инициализировано

                    if (is_string($value)) {
                        // Передано подходящее значение

                        // Запись свойства (успех)
                        $this->namespace = $value;
                    } else {
                        // Передано неподходящее значение

                        // Выброс исключения (неудача)
                        throw new exception('Пространство имён ($this->namespace) должно хранить строку', 500);
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
            'storage', 'db', 'database' => $this->storage ?? throw new exception("Свойство \"\$$name\" не инициализировано", 500),
            'router' => (function () {
                if ($this->__isset('router')) {
                    // Свойство уже было инициализировано
                } else {
                    // Свойство ещё не было инициализировано

                    // Инициализация со значением по умолчанию
                    $this->__set('router', new router);
                }

                // Возврат (успех)
                return $this->router;
            })(),
            'controller' => (function () {
                if ($this->__isset('controller')) {
                    // Свойство уже было инициализировано
                } else {
                    // Свойство ещё не было инициализировано

                    // Инициализация со значением по умолчанию
                    $this->__set('controller', new controller);
                }

                // Возврат (успех)
                return $this->controller;
            })(),
            'model' => (function () {
                if ($this->__isset('model')) {
                    // Свойство уже было инициализировано
                } else {
                    // Свойство ещё не было инициализировано

                    // Инициализация со значением по умолчанию
                    $this->__set('model', new model);
                }

                // Возврат (успех)
                return $this->model;
            })(),
            'namespace' => $this->namespace ?? throw new exception("Свойство \"\$$name\" не инициализировано", 500),
            default => throw new exception("Свойство \"\$$name\" не найдено", 404)
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
