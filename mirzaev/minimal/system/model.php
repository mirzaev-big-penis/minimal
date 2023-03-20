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
  private const POSTFIX = '_model';

  /**
   * Конструктор
   */
  public function __construct()
  {
  }

  /**
   * Записать свойство
   *
   * @param string $name Название
   * @param mixed $value Содержимое
   *
   * @return void
   */
  public function __set(string $name, mixed $value = null): void
  {
    match ($name) {
      'POSTFIX' => throw new exception('Запрещено реинициализировать постфикс ($this::POSTFIX)', 500),
      default => throw new exception("Свойство \"\$$name\" не найдено", 404)
    };
  }

  /**
   * Прочитать свойство
   *
   * @param string $name Название
   *
   * @return mixed Содержимое
   */
  public function __get(string $name): mixed
  {
    return match ($name) {
      'POSTFIX' => $this::POSTFIX ?? throw new exception("Свойство \"POSTFIX\" не инициализировано", 500),
      default => throw new exception("Свойство \"\$$name\" не обнаружено", 404)
    };
  }

  /**
   * Проверить свойство на инициализированность
   *
   * @param string $name Название
   *
   * @return bool Инициализировано свойство?
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
   *
   * @return void
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
