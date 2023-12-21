<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Файлы проекта
use mirzaev\minimal\model;

// Встроенные библиотеки
use exception;

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
  private const POSTFIX = '_controller';

  /**
   * Инстанция модели
   */
  protected model $model;

  /**
   * Инстанция шаблонизатора представления
   */
  protected object $view;

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
   * @param mixed $value Значение
   *
   * @return void
   */
  public function __set(string $name, mixed $value = null): void
  {
    match ($name) {
      'POSTFIX' => throw new exception('Запрещено реинициализировать постфикс ($this::POSTFIX)', 500),
      'model' => (function () use ($value) {
        if ($this->__isset('model')) throw new exception('Запрещено реинициализировать свойство с инстанцией модели ($this->model)', 500);
        else {
          // Свойство не инициализировано

          if (is_object($value)) $this->model = $value;
          else throw new exception('Свойство $this->model должно хранить инстанцию модели (объект)', 500);
        }
      })(),
      'view' => (function () use ($value) {
        if ($this->__isset('view')) throw new exception('Запрещено реинициализировать свойство с инстанцией шаблонизатора представления ($this->view)', 500);
        else {
          // Свойство не инициализировано

          if (is_object($value)) $this->view = $value;
          else throw new exception('Свойство $this->view должно хранить инстанцию шаблонизатора представления (объект)', 500);
        }
      })(),
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
      'model' => $this->model ?? throw new exception("Свойство \"\$model\" не инициализировано", 500),
      'view' => $this->view ?? throw new exception("Свойство \"\$view\" не инициализировано", 500),
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
