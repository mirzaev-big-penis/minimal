<?php

declare(strict_types=1);

namespace mirzaev\minimal\models;

use mirzaev\minimal\core;

use PDO;

use Exception;

/**
 * Модель
 *
 * @package mirzaev\minimal\models
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class model
{
    /**
     * @var PDO $db Соединение с базой данных
     */
    protected PDO $db;

    /**
     * Конструктор
     *
     * @param PDO|null $db Соединение с базой данных
     */
    public function __construct(PDO $db = null)
    {
        $this->db = $db ?? core::db();
    }

    /**
     * Записать свойство
     *
     * @param mixed $name Название
     * @param mixed $value Значение
     */
    public function __set(string $name, mixed $value): void
    {
        if ($name === 'db') {
            if (!isset($this->db)) {
                $this->db = $value;
                return;
            } else {
                throw new Exception('Запрещено переопределять соединение с базой данных');
            }
        }

        throw new Exception('Свойство не найдено: ' . $name);
    }

    /**
     * Прочитать свойство
     *
     * @param string $name Название
     */
    public function __get(string $name): mixed
    {
        if ($name === 'db') {
            return $this->db;
        }

        throw new Exception('Свойство не найдено: ' . $name);
    }


    /**
     * Проверить свойство на инициализированность
     *
     * @param string $name Название
     */
    public function __isset(string $name): bool
    {
        if ($name === 'db') {
            return isset($this->db);
        }

        throw new Exception('Свойство не найдено: ' . $name);
    }
}
