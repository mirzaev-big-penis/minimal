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

        // Универсализация
        $url = self::universalization($url);

        // Сортировка массива маршрутов от большего ключа к меньшему (кешируется)
        krsort($this->routes);

        // Поиск директорий в ссылке
        preg_match_all('/[^\/]+/', $url, $directories);

        // Инициализация директорий
        $directories = $directories[0];

        foreach ($this->routes as $route => $data) {
            // Перебор маршрутов

            // Универсализация
            $route = self::universalization($route);

            // Поиск директорий в маршруте
            preg_match_all('/[^\/]+/', $route, $data['directories']);

            // Инициализация директорий
            $data['directories'] = $data['directories'][0];

            if (count($directories) === count($data['directories'])) {
                // Совпадает количество директорий у ссылки и маршрута (вероятно эта ссылка на этот маршрут)

                // Инициализация массива переменных
                $data['vars'] = [];

                foreach ($data['directories'] as $index => &$directory) {
                    // Перебор найденных переменных

                    if (preg_match('/\$([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]+)/', $directory) === 1) {
                        // Переменная

                        // Запись в массив переменных и перезапись переменной значением из ссылки
                        $directory = $data['vars'][$directory] = $directories[$index];
                    }
                }

                // Реиницилазция маршрута
                $route = self::universalization(implode('/', $data['directories']));

                if (mb_stripos($route, $url, 0, "UTF-8") === 0 && mb_strlen($route, 'UTF-8') <=  mb_strlen($url, 'UTF-8')) {
                    // Найден маршрут, а так же его длина не меньше длины запрошенного URL

                    // Инициализация маршрута
                    if (array_key_exists($_SERVER["REQUEST_METHOD"], $data)) {
                        // Найдены настройки для полученного типа запроса

                        // Запись маршрута
                        $route = $data[$_SERVER["REQUEST_METHOD"]];
                    }

                    // Выход из цикла
                    break;
                }
            }

            // Деинициализация
            unset($route);
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

                if (empty($response = $controller->{$route['method']}($data['vars'] + $_REQUEST))) {
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
     *
     * @param core $core Ядро фреймворка
     *
     * @return string|null HTML-документ с ошибкой
     */
    private function error(core $core = null): ?string
    {
        if (
            class_exists($class = '\\' . ($core->namespace ?? (new ReflectionClass(core::class))->getNamespaceName()) . '\\controllers\\errors' . $core->controller->postfix ?? (new core())->controller->postfix) &&
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

    /**
     * Универсализация URL
     *
     * @param string $url Ссылка
     *
     * @return string Универсализированная ссылка
     */
    private function universalization(string $url): string
    {
        // Если не записан "/" в начале, то записать
        $url = preg_replace('/^([^\/])/', '/$1', $url);

        // Если записан "/" в конце, то удалить
        $url = preg_replace('/(.*)\/$/', '$1', $url);

        return $url;
    }
}
