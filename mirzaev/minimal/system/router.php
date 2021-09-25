<?php

declare(strict_types=1);

namespace mirzaev\minimal;

use mirzaev\shop\core;

/**
 * Маршрутизатор
 *
 * @package mirzaev\shop
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
final class router
{
    /**
     * @var array $router Маршруты
     */
    public static array $routes = [];

    /**
     * Новый маршрут
     *
     * @param string $route Маршрут
     * @param string $controller Контроллер
     * @param string|null $method Метод
     * @param string|null $type Тип
     * @param string|null $model Модель
     *
     * @return void
     */
    public static function create(string $route, string $controller, string $method = null, string $type = 'GET', string $model = null): void
    {
        if (is_null($model)) {
            $model = $controller;
        }

        self::$routes[$route][$type] = [
            // Инициализация контроллера с постфиксом
            'controller' => preg_match('/' . core::controllerPostfix() . '$/i', $controller) ? $controller : $controller . core::controllerPostfix(),
            'model' => preg_match('/' . core::modelPostfix() . '$/i', $model) ? $model : $model . core::modelPostfix(),
            'method' => $method ?? '__construct'
        ];
    }

    /**
     * Обработка маршрута
     *
     * @param string $route Маршрут
     * @param string $controller Контроллер
     *
     * @return void
     */
    public static function handle(string $uri = null): void
    {
        // Если не передан URI, то взять из данных веб-сервера
        $uri = $uri ?? $_SERVER['REQUEST_URI'] ?? '';

        // Инициализация URL
        $url = parse_url($uri, PHP_URL_PATH);

        // Сортировка массива маршрутов от большего ключа к меньшему
        krsort(self::$routes);

        foreach (self::$routes as $key => $value) {
            // Если не записан "/" в начале, то записать
            $route_name = preg_replace('/^([^\/])/', '/$1', $key);
            $url = preg_replace('/^([^\/])/', '/$1', $url);

            // Если не записан "/" в конце, то записать
            $route_name = preg_replace('/([^\/])$/', '$1/', $route_name);
            $url = preg_replace('/([^\/])$/', '$1/', $url);

            if (mb_stripos($route_name, $url, 0, "UTF-8") === 0 && mb_strlen($route_name, 'UTF-8') <=  mb_strlen($url, 'UTF-8')) {
                // Если найден маршрут, а так же его длина не меньше длины запрошенного URL
                $route = $value[$_SERVER["REQUEST_METHOD"] ?? 'GET'];
                break;
            }
        }

        if (!empty($route)) {
            // Если маршрут найден
            if (class_exists($controller_class = core::namespace() . '\\controllers\\' . $route['controller'])) {
                // Если найден класс-контроллер маршрута

                $controller = new $controller_class;

                if (empty($response = $controller->{$route['method']}($_REQUEST))) {
                    // Если не получен ответ после обработки контроллера

                    // Удаление постфикса для поиска директории
                    $dir = preg_replace('/' . core::controllerPostfix() . '$/i', '', $route['controller']);

                    // Отрисовка шаблона по умолчанию
                    $response = $controller->view($dir);
                }

                echo $response;
                return;
            }
        }

        echo self::error();
    }

    private static function error(): ?string
    {
        if (
            class_exists($class = core::namespace() . '\\controllers\\errors' . core::controllerPostfix()) &&
            method_exists($class, $method = 'error404')
        ) {
            // Если существует контроллер ошибок и метод-обработчик ответа 404,
            // то вызвать обработку ответа 404
            return (new $class(basename($class)))->$method();
        } else {
            // Никаких исключений не вызывать, отдать пустую страницу
            // Либо можно, но отображать в зависимости от включенного дебаг режима
            return null;
        }
    }
}
