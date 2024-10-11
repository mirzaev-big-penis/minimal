<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Файлы проекта
use mirzaev\minimal\core;

/**
 * Маршрутизатор
 *
 * @package mirzaev\minimal
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
final class router
{
	/**
	 * @var array $router Реестр маршрутов
	 */
	/* protected array $routes = []; */
	public array $routes = [];

	/**
	 * Записать маршрут
	 *
	 * @param string $route Маршрут
	 * @param string $handler Обработчик - инстанции контроллера и модели (не обязательно), без постфиксов
	 * @param ?string $method Вызываемый метод в инстанции контроллера обработчика
	 * @param ?string $request HTTP-метод запроса (GET, POST, PUT...)
	 * @param ?string $model Инстанция модели (переопределение инстанции модели в $target)
	 * @param array $variables 
	 *
	 * @return static The instance from which the method was called (fluent interface)
	 */
	public function write(
		string $route,
		string $handler,
		?string $method = 'index',
		?string $request = 'GET',
		?string $model = null,
		array $variables = []
	): static {
		// Запись в реестр
		$this->routes[$route][$request] = [
			'controller' => $handler,
			'model' => $model ?? $handler,
			'method' => $method,
			'variables' => $variables
		];

		// Exit (success) (fluent interface)
		return $this;
	}

	/**
	 * Handle a request
	 *
	 * @param string|null $uri URI (protocol://domain/foo/bar)
	 * @param string|null $method Method (GET, POST, PUT...)
	 * @param core|null $core Instence of the system core
	 *
	 * @return string|int|null Response
	 */
	public function handle(
		?string $uri = null,
		?string $method = null,
		?core $core = new core
	): string|int|null {
		// Declaration of the registry of routes directoies 
		$routes = [];

		foreach ($this->routes as $route => $data) {
			// Iteration over routes

			// Search directories of route (explode() creates empty value in array)
			preg_match_all('/(^\/$|[^\/]+)/', $route, $data['directories']);
			$routes[$route] = $data['directories'][0];
		}

		if (count($routes) === count($this->routes)) {
			// Initialized the registry of routes directoies 

			// Initializing default values		
			$uri ??= $_SERVER['REQUEST_URI'] ?? '/';
			$method ??= $_SERVER["REQUEST_METHOD"];

			// Initializing of URN (/foo/bar)
			$urn = parse_url(urldecode($uri), PHP_URL_PATH);

			// Universalization of URN
			$urn = self::universalize($urn);

			// Search directories of URN (explode() creates empty value in array)
			preg_match_all('/(^\/$|[^\/]+)/', $urn, $directories);
			$directories = $directories[0];

			/**
			 * Initialization of the route
			 */

			// Initializing the buffer of matches of route directories with URN directories
			$matches = [];

			foreach ($directories as $i => $urn_directory) {
				// Iteration over directories of URN 

				foreach ($this->routes as $route => $data) {
					// Iteration over routes

					if (isset($data[$method])) {
						// The request method matches the route method

						// Universalization of route
						$route = self::universalize($route);

						// Skipping unmatched routes based on results of previous iterations
						if (isset($matches[$route]) && $matches[$route] === false) continue;

						// Initializing of route directory
						$route_directory = $routes[$route][$i] ?? null;

						if (isset($route_directory)) {
							// Initialized of route directory

							if ($urn_directory === $route_directory) {
								// The directory of URN is identical to the directory of route

								// Writing: end of URN directories XNOR end of route directories
								$matches[$route] = !(isset($directories[$_i = $i + 1]) xor isset($routes[$route][$_i]));
							} else if (preg_match('/^\$([a-zA-Z_\x80-\xff]+)$/', $route_directory) === 1) {
								// The directory of route is a variable ($variable)

								// Writing: end of URN directories XNOR end of route directories
								$matches[$route] = !(isset($directories[$_i = $i + 1]) xor isset($routes[$route][$_i]));
							} else if (
								!isset($routes[$route][$i + 1])
								&& preg_match('/^\$([a-zA-Z_\x80-\xff]+\.\.\.)$/', $route_directory) === 1
							) {
								// The directory of route is a collector ($variable...)
								// AND this is the end of route directories

								// Writing
								$matches[$route] = 'collector';
							} else $matches[$route] = false;
						} else if ($matches[$route] === 'collector') {
						} else $matches[$route] = false;
					}
				}
			}

			// Finding a priority route from match results
			foreach ($matches as $route => $match) if ($match !== false) break;

			if ($route && !empty($data = $this->routes[$route])) {
				// Route found

				// Universalization of route
				$route = self::universalize($route);

				/**
				 * Initialization of route variables
				 */

				foreach ($routes[$route] as $i => $route_directory) {
					// Iteration over directories of route

					if (preg_match('/^\$([a-zA-Z_\x80-\xff]+)$/', $route_directory) === 1) {
						// The directory is a variable ($variable)

						// Запись в реестр переменных и перещапись директории в маршруте
						$data[$method]['variables'][trim($route_directory, '$')] = $directories[$i];
					} else if (preg_match('/^\$([a-zA-Z_\x80-\xff]+\.\.\.)$/', $route_directory) === 1) {
						// The directory of route is a collector ($variable...)

						// Инициализаия ссылки на массив сборщика
						$collector = &$data[$method]['variables'][trim($route_directory, '$.')];

						// Инициализаия массива сборщика
						$collector ??= [];

						// Запись в реестр переменных
						$collector[] = $directories[$i];

						foreach (array_slice($directories, ++$i, preserve_keys: true) as &$urn_directory) {
							// Перебор директорий URN

							// Запись в реестр переменных
							$collector[] = $urn_directory;
						}

						break;
					}
				}

				/**
				 * Initialization of route handlers
				 */

				if (array_key_exists($method, $data)) {
					// Идентифицирован метод маршрута (GET, POST, PUT...)

					// Инициализация обработчиков (controller, model, method)
					$handlers = $data[$method];

					if (class_exists($controller = $core->namespace . '\\controllers\\' . $handlers['controller'] . $core->controller::POSTFIX)) {
						// Найден контроллер

						// Инициализация инстанции ядра контроллера
						$controller = new $controller;

						// Вызов связанного с маршрутом метода и возврат (успех)
						return $controller->{$handlers['method']}($handlers['variables'] + $_REQUEST, $_FILES, file_get_contents('php://input'));
					}
				}
			}
		}

		// Возврат (провал)
		return $this->error($core);
	}

	/**
	 * Sorting routes
	 *
	 * 1. Short routes
	 * 2. Long routes
	 * 3. Short routes with variables (position of variables from "right" to "left")
	 * 4. Long routes with variables (position of variables from "right" to "left")
	 * 5. Short routes with collector
	 * 6. Long routes with collector
	 * 7. Short routes with variables and collector (position of variables from "right" to "left" then by amount)
	 * 8. Long routes with variables and collector (position of variables from "right" to "left")
	 *
	 * Добавить чтобы сначала текст потом переменная затем после переменной первыми тексты и в конце перменные опять и так рекурсивно
	 *
	 * @return static The instance from which the method was called (fluent interface)
   *
	 * @todo ПЕРЕДЕЛАТЬ ПОЛНОСТЬЮ
	 */
	public function sort(): static
	{
		uksort($this->routes, function (string $a, string $b) {
			// Sorting routes

			// Initialization of string lengths (multibyte-safe)
			$length = [
				$a => mb_strlen($a),
				$b => mb_strlen($b)
			];

			// Initialization of the presence of variables
			$variables = [
				$a => preg_match('/\$([a-zA-Z_\x80-\xff]+)(\/|$)/', $a) === 1,
				$b => preg_match('/\$([a-zA-Z_\x80-\xff]+)(\/|$)/', $b) === 1
			];

			// Initialization of the presence of collectors
			$collectors = [
				$a => preg_match('/\$([a-zA-Z_\x80-\xff]+)\.\.\.$/', $a) === 1,
				$b => preg_match('/\$([a-zA-Z_\x80-\xff]+)\.\.\.$/', $b) === 1
			];


			if ($variables[$a] && !$variables[$b]) return 1;
			else if (!$variables[$a] && $variables[$b]) return -1;
			else if ($variables[$a] && $variables[$b]) {
			} else if ($collectors[$a] && !$collectors[$b]) return 1;
			else if (!$collectors[$a] && $collectors[$b]) return -1;
			else {
				// NOR variables and XAND collectors (both are not found or both are found)

				// The routes are the same length (no changes)
				if ($length[$a] === $length[$b]) return 0;

				// The longer route moves to the end
				return $length[$a] > $length[$b] ? 1 : -1;
			}
		});

		// Exit (success) (fluent interface)
		return $this;
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
	 * Universalize URN 
	 *
	 * Always "/" at the beginning and never "/" at the end
	 *
	 * @param string &$urn URN (/foo/bar)
	 *
	 * @return string Universalized URN 
	 */
	private function universalize(string $urn): string
	{
		// Universalization of URN and exit (success)
		return (string) preg_replace('/(.+)\/$/', '$1', preg_replace('/^([^\/])/', '/$1', $urn));
	}
}
