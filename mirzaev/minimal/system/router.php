<?php

declare(strict_types=1);

namespace mirzaev\minimal;

use mirzaev\minimal\core;

use ReflectionClass;

/**
 * Маршрутизатор
 *
 * @package mirzaev\shop
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 *
 * @todo
 * 1. Доработать обработку ошибок
 * 2. Добавить __set(), __get(), __isset() и __unset()
 */
final class router
{
    /**
     * @var array $router Маршруты
     */
    public array $routes = [];

    /**
     * Записать маршрут
     *
     * @param string $route Маршрут
     * @param string $target Обработчик (контроллер и модель, без постфиксов)
     * @param string|null $method Метод
     * @param string|null $type Тип
     * @param string|null $model Модель
     */
    public function write(string $route, string $target, string $method = null, string $type = 'GET', string $model = null): void
    {
        // Запись в реестр
        $this->routes[$route][$type] = [
            'target' => $target,
            'method' => $method ?? '__construct'
        ];
    }

    /**
     * Обработать маршрут
     *
     * @param string $route Маршрут
     */
    public function handle(string $uri = null, core $core = null): ?string
    {
        // Запись полученного URI или из данных веб-сервера
        $uri = $uri ?? $_SERVER['REQUEST_URI'] ?? '';

        // Инициализация URL
        $url = parse_url($uri, PHP_URL_PATH);

        // Сортировка массива маршрутов от большего ключа к меньшему
        krsort($this->routes);

        foreach ($this->routes as $key => $value) {
            // Перебор маршрутов

            // Если не записан "/" в начале, то записать
            $route_name = preg_replace('/^([^\/])/', '/$1', $key);
            $url = preg_replace('/^([^\/])/', '/$1', $url);

            // Если не записан "/" в конце, то записать
            $route_name = preg_replace('/([^\/])$/', '$1/', $route_name);
            $url = preg_replace('/([^\/])$/', '$1/', $url);

            if (mb_stripos($route_name, $url, 0, "UTF-8") === 0 && mb_strlen($route_name, 'UTF-8') <=  mb_strlen($url, 'UTF-8')) {
                // Найден маршрут, а так же его длина не меньше длины запрошенного URL

                // Инициализация маршрута
                $route = $value[$_SERVER["REQUEST_METHOD"] ?? 'GET'];

                // Выход из цикла (успех)
                break;
            }
        }

        if (!empty($route)) {
            // Найден маршрут

            if (class_exists($controller = ($core->namespace ?? (new core)->namespace) . '\\controllers\\' . $route['target'] . $core->controller->postfix ?? (new core())->controller->postfix)) {
                // Найден контроллер

                // Инициализация контроллера
                $controller = new $controller;

                if (class_exists($model = ($core->namespace ?? (new core)->namespace) . '\\models\\' . $route['target'] . $core->model->postfix ?? (new core())->model->postfix)) {
                    // Найдена модель

                    // Инициализация модели
                    $controller->model = new $model;
                }

                if (empty($response = $controller->{$route['method']}($_REQUEST))) {
                    // Не удалось получить ответ после обработки контроллера

                    // Возврат (неудача)
                    return $this->error($core);
                }

                // Возврат (успех)
                return $response;
            }
        }

        // Возврат (неудача)
        return $this->error($core);
    }

    /**
     * Контроллер ошибок
     */
    private function error(core $core = null): ?string
    {
        if (
            class_exists($class = (new ReflectionClass(core::class))->getNamespaceName() . '\\controllers\\errors' . $core->controller->postfix ?? (new core())->controller->postfix) &&
            method_exists($class, $method = 'error404')
        ) {
            // Существует контроллер ошибок и метод для обработки ошибки

            // Возврат (вызов метода для обработки ошибки)
            return (new $class(basename($class)))->$method();
        } else {
            // Не существует контроллер ошибок или метод для обработки ошибки

            // Никаких исключений не вызывать, отдать пустую страницу,
            // либо вызвать, но отображать в зависимости от включенного дебаг режима !!!!!!!!!!!!!!!!!!!! см. @todo
            return null;
        }
    }
}
