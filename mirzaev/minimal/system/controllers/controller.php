<?php

declare(strict_types=1);

namespace mirzaev\minimal\controllers;

use mirzaev\minimal\core;
use mirzaev\minimal\models\model;

use Twig\Loader\FilesystemLoader;
use Twig\Environment as view;

use Exception;

/**
 * Контроллер
 *
 * @package mirzaev\minimal\controllers
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class controller
{
    /**
     * @var model $model Модель
     */
    protected model $model;

    /**
     * @var view $view Шаблонизатор представления
     */
    protected view $view;

    /**
     * Конструктор
     *
     * @return void
     */
    public function __construct()
    {
        // Установка значения по умолчанию для модели (если будет найдена)
        $this->__get('model');
        // Установка значения по умолчанию для шаблонизатора представлений
        $this->__get('view');
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
     * @param mixed $name Название
     * @param mixed $value Значение
     *
     * @return void
     */
    public function __set($name, $value): void
    {
        if ($name === 'model') {
            if (!isset($this->model)) {
                $this->model = $value;
                return;
            } else {
                throw new Exception('Запрещено переопределять модель');
            }
        } else if ($name === 'view') {
            if (!isset($this->view)) {
                $this->view = $value;
                return;
            } else {
                throw new Exception('Запрещено переопределять шаблонизатор представления');
            }
        }

        throw new Exception('Свойство не найдено: ' . $name);
    }

    /**
     * Прочитать свойство
     *
     * @param mixed $name Название
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'model') {
            if (isset($this->model)) {
                // Если модель найдена
                return $this->model;
            } else {
                // Инициализация класса модели
                $model = preg_replace('/' . core::controllerPostfix() . '$/i', '', basename(get_class($this))) . core::modelPostfix();
                // Иначе
                if (class_exists($model_class = core::namespace() . '\\models\\' . $model)) {
                    // Если найдена одноимённая с контроллером модель (без постфикса)
                    return $this->model = new $model_class;
                }
                return;
            }
        } else if ($name === 'view') {
            if (isset($this->view)) {
                // Если модель найдена
                return $this->view;
            } else {
                $path = core::path() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views';
                $loader = new FilesystemLoader($path);

                return $this->view = (new view($loader, [
                    // 'cache' => $path . DIRECTORY_SEPARATOR . 'cache',
                ]));
            }
        }

        throw new Exception('Свойство не найдено: ' . $name);
    }


    /**
     * Проверить свойство на инициализированность
     *
     * @param string $name Название
     *
     * @return mixed
     */
    public function __isset(string $name)
    {
        if ($name === 'model') {
            return isset($this->model);
        } else if ($name === 'view') {
            return isset($this->view);
        }

        throw new Exception('Свойство не найдено: ' . $name);
    }
}
