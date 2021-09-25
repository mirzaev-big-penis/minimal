<?php

declare(strict_types=1);

namespace mirzaev\minimal;

use mirzaev\minimal\router;

use PDO;
use PDOException;

use Exception;

/**
 * Ядро
 *
 * @package mirzaev\minimal
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
final class core
{
    /**
     * @var PDO $db Соединение с базой данных
     */
    private static PDO $db;

    /**
     * @var router $router Маршрутизатор
     */
    private static router $router;

    /**
     * @var string $path Корневая директория
     */
    private static string $path;

    /**
     * @var string $namespace Пространство имён
     */
    private static string $namespace;

    /**
     * @var string $postfix_controller Постфикс контроллеров
     */
    private static string $postfix_controller = '_controller';

    /**
     * @var string $postfix_model Постфикс моделей
     */
    private static string $postfix_model = '_model';

    /**
     * Конструктор
     *
     * @param string $db
     * @param string $login
     * @param string $password
     * @param router $router Маршрутизатор
     */
    public function __construct(string $db = 'mysql:dbname=db;host=127.0.0.1', string $login = '', string $password = '', router $router = null)
    {
        // Инициализация маршрутизатора
        self::$router = $router ?? new router;

        // Инициализация корневого пространства имён
        self::$namespace = __NAMESPACE__;

        try {
            // Инициализация PDO
            self::$db = new PDO($db, $login, $password);
        } catch (PDOException $e) {
            throw new Exception('Проблемы при соединении с базой данных: ' . $e->getMessage(), $e->getCode());
        }

        // Обработка запроса
        self::$router::handle();
    }

    /**
     * Деструктор
     *
     */
    public function __destruct()
    {
        // Закрытие соединения
    }

    /**
     * Прочитать/записать корневую директорию
     *
     * @var string|null $path Путь
     *
     * @return string
     */
    public static function path(string $path = null): string
    {
        return self::$path = (string) ($path ?? self::$path);
    }

    /**
     * Прочитать/записать соединение с базой данных
     *
     * @var PDO|null $db Соединение с базой данных
     *
     * @return PDO
     */
    public static function db(PDO $db = null): PDO
    {
        return self::$db = $db ?? self::$db;
    }

    /**
     * Прочитать постфикс контроллеров
     *
     * @return string|null
     */
    public static function controllerPostfix(): ?string
    {
        return self::$postfix_controller;
    }

    /**
     * Прочитать постфикс моделей
     *
     * @return string|null
     */
    public static function modelPostfix(): ?string
    {
        return self::$postfix_model;
    }

    /**
     * Прочитать пространство имён
     *
     * @return string|null
     */
    public static function namespace(): ?string
    {
        return self::$namespace;
    }
}
