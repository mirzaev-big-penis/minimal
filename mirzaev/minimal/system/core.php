<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Файлы проекта
use mirzaev\minimal\router,
  mirzaev\minimal\controller,
  mirzaev\minimal\model;

// Встроенные библиотеки
use exception,
  ReflectionClass as reflection;

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
   * Инстанция соединения с базой данных
   */
  private object $db;

  /**
   * Инстанция маршрутизатора
   */
  private readonly router $router;

  /**
   * Инстанция ядра контроллера
   */
  private readonly controller $controller;

  /**
   * Инстанция ядра модели
   */
  private readonly model $model;

  /**
   * Путь пространства имён (системное)
   *
   * Используется для поиска файлов по спецификации PSR-4
   */
  private readonly string $namespace;

  /**
   * Конструктор
   *
   * @param ?object $db Инстанция соединения с базой данных
   * @param ?router $router Маршрутизатор
   * @param ?controller $controller Инстанция ядра контроллера
   * @param ?model $model Инстанция ядра модели
   * @param ?string $namespace Пространство имён системного ядра
   * 
   * @return self Инстанция ядра
   */
  public function __construct(
    ?object $db = null,
    ?router $router = null,
    ?controller $controller = null,
    ?model $model = null,
    ?string $namespace = null
  ) {
    // Инициализация свойств
    if (isset($db)) $this->__set('db', $db);
    if (isset($router)) $this->__set('router', $router);
    if (isset($controller)) $this->__set('controller', $controller);
    if (isset($model)) $this->__set('model', $model);
    $this->__set('namespace', $namespace ?? (new reflection(self::class))->getNamespaceName());
  }

  /**
   * Деструктор
   *
   */
  public function __destruct()
  {
  }

  /**
   * Запуск
   *
   * @param ?string $uri Маршрут
   *
   * @return ?string Сгенерированный ответ (HTML, JSON...)
   */
  public function start(string $uri = null): ?string
  {
    // Обработка запроса
    return $this->__get('router')->handle($uri, core: $this);
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
      'db', 'database' => (function () use ($value) {
        if ($this->__isset('db')) throw new exception('Запрещено реинициализировать инстанцию соединения с базой данных ($this->db)', 500);
        else {
          // Свойство ещё не было инициализировано

          if (is_object($value)) $this->db = $value;
          else throw new exception('Свойство $this->db должно хранить инстанцию соединения с базой данных', 500);
        }
      })(),
      'router' => (function () use ($value) {
        if ($this->__isset('router')) throw new exception('Запрещено реинициализировать инстанцию маршрутизатора ($this->router)', 500);
        else {
          // Свойство ещё не было инициализировано

          if ($value instanceof router) $this->router = $value;
          else throw new exception('Свойство $this->router должно хранить инстанцию маршрутизатора (mirzaev\minimal\router)"', 500);
        }
      })(),
      'controller' => (function () use ($value) {
        if ($this->__isset('controller')) throw new exception('Запрещено реинициализировать инстанцию ядра контроллеров ($this->controller)', 500);
        else {
          // Свойство не инициализировано

          if ($value instanceof controller) $this->controller = $value;
          else throw new exception('Свойство $this->controller должно хранить инстанцию ядра контроллеров (mirzaev\minimal\controller)', 500);
        }
      })(),
      'model' => (function () use ($value) {
        if ($this->__isset('model')) throw new exception('Запрещено реинициализировать инстанцию ядра моделей ($this->model)', 500);
        else {
          // Свойство не инициализировано

          if ($value instanceof model) $this->model = $value;
          else throw new exception('Свойство $this->model должно хранить инстанцию ядра моделей (mirzaev\minimal\model)', 500);
        }
      })(),
      'namespace' => (function () use ($value) {
        if ($this->__isset('namespace')) throw new exception('Запрещено реинициализировать путь пространства имён ($this->namespace)', 500);
        else {
          // Свойство не инициализировано

          if (is_string($value)) $this->namespace = $value;
          else throw new exception('Свойство $this->namespace должно хранить строку с путём пространства имён', 500);
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
      'db', 'database' => $this->db ?? throw new exception("Свойство \"\$$name\" не инициализировано", 500),
      'router' => (function () {
        // Инициализация со значением по умолчанию
        if (!$this->__isset('router')) $this->__set('router', new router);

        // Возврат (успех)
        return $this->router;
      })(),
      'controller' => (function () {
        // Инициализация со значением по умолчанию
        if (!$this->__isset('controller')) $this->__set('controller', new controller);

        // Возврат (успех)
        return $this->controller;
      })(),
      'model' => (function () {
        // Инициализация со значением по умолчанию
        if (!$this->__isset('model')) $this->__set('model', new model);

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
