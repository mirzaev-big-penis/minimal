<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Files of the project
use mirzaev\minimal\router,
	mirzaev\minimal\route,
	mirzaev\minimal\controller,
	mirzaev\minimal\model;

// Built-in libraries
use exception,
	BadMethodCallException  as exception_method,
	DomainException as exception_domain,
	InvalidArgumentException as exception_argument,
	UnexpectedValueException as exception_value,
	ReflectionClass as reflection;

/**
 * Core
 *
 * @param string $namespace Namespace where the core was initialized from
 * @param controller $controller An instance of the controller
 * @param model $model An instance of the model
 * @param router $router An instance of the router
 *
 * @mathod self __construct(?string $namespace) Constructor
 * @method void __destruct() Destructor
 * @method string|null request(?string $uri, ?string $method, array $variabls) Handle the request
 * @method string|null route(route $route, string $method) Handle the route
 *
 * @package mirzaev\minimal
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
final class core
{
	/**
	 * Namespace
	 *
	 * @var string $namespace Namespace where the core was initialized from
	 *
	 * @see https://www.php-fig.org/psr/psr-4/
	 */
	public string $namespace {
		// Read
		get => $this->namespace;
	}

	/**
	 * Controller
	 *
	 * @var controller $controller An instance of the controller
	 */
	private controller $controller {
		// Read
		get => $this->controller ??= new controller;
	}

	/**
	 * Model
	 *
	 * @var model $model An instance of the model
	 */
	private model $model {
		// Read
		get => $this->model ??= new model;
	}

	/**
	 * Router
	 *
	 * @var router $router An instance of the router
	 */
	public router $router {
		get => $this->router ??= router::initialize();
	}

	/**
	 * Constrictor
	 *
	 * @param ?string $namespace Пространство имён системного ядра
	 * 
	 * @return self The instance of the core
	 */
	public function __construct(
		?string $namespace = null
	) {
		// Writing a namespace to the property
		$this->namespace = $namespace ?? (new reflection(self::class))->getNamespaceName();
	}


	/**
	 * Destructor
	 */
	public function __destruct() {}

	/**
	 * Request
	 *
	 * Handle the request
	 *
	 * @param string|null $uri URI of the request (value by default: $_SERVER['REQUEST_URI'])
	 * @param string|null $method Method of the request (GET, POST, PUT...) (value by default: $_SERVER["REQUEST_METHOD"])
	 * @paam array $parameters parameters for merging with route parameters 
	 *
	 * @return string|null Response 
	 */
	public function request(?string $uri = null, ?string $method = null, array $parameters = []): ?string
	{
		// Matching a route 
		$route = $this->router->match($uri ??= $_SERVER['REQUEST_URI'] ?? '/', $method ??= $_SERVER["REQUEST_METHOD"]);

		if ($route) {
			// Initialized the route

			if (!empty($parameters)) {
				// Recaived parameters

				// Merging parameters with route parameters
				$route->parameters = $parameters + $route->parameters;
			}

			// Handling the route and exit (success)
			return $this->route($route, $method);
		}

		// Exit (fail)
		return null;
	}

	/**
	 * Route
	 *
	 * Handle the route
	 *
	 * @param route $route The route
	 * @param string $method Method of requests (GET, POST, PUT, DELETE, COOKIE...)
	 *
	 * @return string|null Response, if generated
	 */
	public function route(route $route, string $method = 'GET'): ?string
	{
		// Initializing name of the controller class
		$controller = $route->controller;
	
		if ($route->controller instanceof controller) {
			// Initialized the controller
		} else if (class_exists($controller = "$this->namespace\\controllers\\$controller")) {
			// Found the controller by its name

			// Initializing the controller
			$route->controller = new $controller(core: $this);
		} else if (!empty($route->controller)) {
			// Not found the controller and $route->controller has a value

			// Exit (fail)
			throw new exception_domain('Failed to found the controller: ' . $route->controller);
		} else {
			// Not found the controller and $route->controller is empty

			// Exit (fail)
			throw new exception_argument('Failed to initialize the controller: ' . $route->controller);
		}

		// Deinitializing name of the controller class
		unset($controller);

		// Initializing name if the model class
		$model = $route->model;

		if ($route->model instanceof model) {
			// Initialized the model
		} else if (class_exists($model = "$this->namespace\\models\\$model")) {
			// Found the model by its name

			// Initializing the model
			$route->model = new $model;
		} else if (!empty($route->model)) {
			// Not found the model and $route->model has a value

			// Exit (fail)
			throw new exception_domain('Failed to initialize the model: ' . ($route->model ?? $route->controller));
		}

		// Deinitializing name of the model class
		unset($model);

		if ($route->model instanceof model) {
			// Initialized the model

			// Writing the model to the controller
			$route->controller->model = $route->model;
		}

		if ($method === 'POST') {
			// POST

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters + $_POST, $_FILES);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'GET') {
			// GET

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				if ($_SERVER["CONTENT_TYPE"] === 'multipart/form-data' || $_SERVER["CONTENT_TYPE"] === 'application/x-www-form-urlencoded') {
					// The requeset content type is the "multipart/form-data" or "application/x-www-form-urlencoded"

					// Analysis of the request
					[$_GET, $_FILES] = request_parse_body($route->options);
				}

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters + $_GET, $_FILES);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'PUT') {
			// PUT

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				if ($_SERVER["CONTENT_TYPE"] === 'multipart/form-data' || $_SERVER["CONTENT_TYPE"] === 'application/x-www-form-urlencoded') {
					// The requeset content type is the "multipart/form-data" or "application/x-www-form-urlencoded"

					// Analysis of the request
					[$_PUT, $_FILES] = request_parse_body($route->options);
				}

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters + $_PUT, $_FILES);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'DELETE') {
			// DELETE

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				if ($_SERVER["CONTENT_TYPE"] === 'multipart/form-data' || $_SERVER["CONTENT_TYPE"] === 'application/x-www-form-urlencoded') {
					// The requeset content type is the "multipart/form-data" or "application/x-www-form-urlencoded"

					// Analysis of the request
					[$_DELETE, $_FILES] = request_parse_body($route->options);
				}

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters + $_DELETE, $_FILES);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'PATCH') {
			// PATCH

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				if ($_SERVER["CONTENT_TYPE"] === 'multipart/form-data' || $_SERVER["CONTENT_TYPE"] === 'application/x-www-form-urlencoded') {
					// The requeset content type is the "multipart/form-data" or "application/x-www-form-urlencoded"

					// Analysis of the request
					[$_PATCH, $_FILES] = request_parse_body($route->options);
				}

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters + $_PATCH, $_FILES);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'HEAD') {
			// HEAD

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'OPTIONS') {
			// OPTIONS

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'CONNECT') {
			// CONNECT

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else if ($method === 'TRACE') {
			// TRACE

			if (method_exists($route->controller, $route->method)) {
				// Found the method of the controller

				// Executing method of the controller that handles the route and exit (success)
				return $route->controller->{$route->method}($route->parameters);
			} else {
				// Not found the method of the controller
		
				// Exit (fail)
				throw new exception('Failed to find the method of the controller: ' . $route->method);
			}
		} else {
			// Not recognized method of the request

			// Exit (fail)
			throw new exception_value('Failed to recognize the method: ' . $method);
		}

		// Exit (fail)
		return null;
	}
}
