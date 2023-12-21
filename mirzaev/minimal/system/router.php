<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Файлы проекта
use mirzaev\minimal\core;

/**
 * Маршрутизатор
 *
 * @package mirzaev\minimal
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
final class router
{
  /**
   * @var array $router Реестр маршрутов
   */
  protected array $routes = [];

  /**
   * Записать маршрут
   *
   * @param string $route Маршрут
   * @param string $handler Обработчик - инстанции контроллера и модели (не обязательно), без постфиксов
   * @param ?string $method Вызываемый метод в инстанции контроллера обработчика
   * @param ?string $request HTTP-метод запроса (GET, POST, PUT...)
   * @param ?string $model Инстанция модели (переопределение инстанции модели в $target)
   *
   * @return void
   */
  public function write(
    string $route,
    string $handler,
    ?string $method = 'index',
    ?string $request = 'GET',
    ?string $model = null
  ): void {
    // Запись в реестр
    $this->routes[$route][$request] = [
      'controller' => $handler,
      'model' => $model ?? $handler,
      'method' => $method
    ];
  }

  /**
   * Обработать маршрут
   *
   * @param ?string $uri URI запроса (https://domain.com/foo/bar)
   * @param ?string $method Метод запроса (GET, POST, PUT...)
	 * @param ?core $core Инстанция системного ядра
	 *
	 * @return string|int|null Ответ
   */
  public function handle(?string $uri = null, ?string $method = null, ?core $core = new core): string|int|null
  {
    // Инициализация значений по умолчанию
    $uri ??= $_SERVER['REQUEST_URI'] ?? '/';
    $method ??= $_SERVER["REQUEST_METHOD"] ?? 'GET';

    // Инициализация URL запроса (/foo/bar)
    $url = parse_url($uri, PHP_URL_PATH);

    // Универсализация маршрута
    $url = self::universalize($url);

    // Сортировка реестра маршрутов от большего ключа к меньшему (кешируется)
    krsort($this->routes);

    // Поиск директорий в ссылке
    preg_match_all('/[^\/]+/', $url, $directories);

    // Инициализация директорий
    $directories = $directories[0];

    foreach ($this->routes as $route => $data) {
      // Перебор маршрутов

      // Универсализация маршрута
      $route = self::universalize($route);

      // Поиск директорий
      preg_match_all('/[^\/]+/', $route, $data['directories']);

      // Инициализация директорий
      $data['directories'] = $data['directories'][0];

      if (count($directories) === count($data['directories'])) {
        // Входит в диапазон маршрут (совпадает количество директорий у ссылки и маршрута)

        // Инициализация реестра переменных
        $data['vars'] = [];

        foreach ($data['directories'] as $index => &$directory) {
          // Перебор директорий

          if (preg_match('/\$([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]+)/', $directory) === 1) {
            // Директория является переменной (.../$variable/...)

            // Запись в реестр переменных
            $directory = $data['vars'][trim($directory, '$')] = $directories[$index];
          }
        }

        // Реиницилазция маршрута
        $route = self::universalize(implode('/', $data['directories']));

        // Проверка на пустой маршрут
        if (empty($route)) $route = '/';

        if (mb_stripos($route, $url, 0, "UTF-8") === 0 && mb_strlen($route, 'UTF-8') <= mb_strlen($url, 'UTF-8')) {
          // Идентифицирован маршрут (длина не меньше длины запрошенного URL)

          if (array_key_exists($method, $data)) {
            // Идентифицирован метод маршрута (GET, POST, PUT...)

						$route = $data[$method];

            if (class_exists($controller = $core->namespace . '\\controllers\\' . $route['controller'] . $core->controller::POSTFIX)) {
              // Найден контроллер

              // Инициализация инстанции ядра контроллера
							$controller = new $controller;

              // Инициализация инстанции ядра модели
              if (class_exists($model = $core->namespace . '\\models\\' . $route['model'] . $core->model::POSTFIX));

              // Вызов связанного с маршрутом методв и возврат (успех)
              return $controller->{$route['method']}($data['vars'] + $_REQUEST, $_FILES);
            }
          }

          // Выход из цикла (провал)
          break;
        }
      }
    }

    // Возврат (провал)
    return $this->error($core);
  }

  /**
   * Сгенерировать ответ с ошибкой
   *
   * Вызывает метод error404 в инстанции контроллера ошибок
   *
   * @param ?core $core Инстанция системного ядра
   *
   * @return ?string HTML-документ
   */
  private function error(core $core = new core): ?string
  {
    return class_exists($class = '\\' . $core->namespace . '\\controllers\\errors' . $core->controller::POSTFIX)
      && method_exists($class, $method = 'error404')
      ? (new $class)->$method()
      : null;
  }

  /**
   * Универсализировать маршрут 
   *
   * @param string $route Маршрут
   *
   * @return string Универсализированный маршрут
   */
  private function universalize(string $route): string
  {
    // Если не записан "/" в начале, то записать, затем, если записан "/" в конце, то удалить
    return preg_replace('/(.+)\/$/', '$1', preg_replace('/^([^\/])/', '/$1', $route));
  }
}
