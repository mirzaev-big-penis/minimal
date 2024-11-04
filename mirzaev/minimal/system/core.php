<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Files of the project
use mirzaev\minimal\router,
	mirzaev\minimal\route,
	mirzaev\minimal\controller,
	mirzaev\minimal\model,
	mirzaev\minimal\http\request,
	mirzaev\minimal\http\response,
	mirzaev\minimal\http\enumerations\status;

// Built-in libraries
use exception,
	BadMethodCallException as exception_method,
	DomainException as exception_domain,
	InvalidArgumentException as exception_argument,
	UnexpectedValueException as exception_value,
	LogicException as exception_logic,
	ReflectionClass as reflection;

/**
 * Core
 *
 * @param string $namespace Namespace where the core was initialized from
 * @param controller $controller An instance of the controller
 * @param model $model An instance of the model
 * @param router $router An instance of the router
 *
 * @mathod void __construct(?string $namespace) Constructor
 * @method void __destruct() Destructor
 * @method string|null start() Initialize request by environment and handle it
 * @method string|null request(request $request, array $parameters = []) Handle request
 * @method string|null route(route $route, string $method) Handle route
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
	 * @return void
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
	 * Start
	 *
	 * Initialize request by environment and handle it
	 *
	 * @return string|null Response 
	 */
	public function start(): ?string
	{
		// Handle request and exit (success)
		return $this->request(new request(environment: true));
	}

	/**
	 * Request
	 *
	 * Handle request
	 *
	 * @param request $request The request
	 * @paam array $parameters parameters for merging with route parameters
	 *
	 * @return string|null Response 
	 */
	public function request(request $request, array $parameters = []): ?string
	{
		// Matching a route 
		$route = $this->router->match($request);

		if ($route) {
			// Initialized a route

			if (!empty($parameters)) {
				// Recaived parameters

				// Merging parameters with route parameters
				$route->parameters = $parameters + $route->parameters;
			}

			// Writing request options from route options
			$request->options = $route->options;

			// Handling a route and exit (success)
			return $this->route($route, $request);
		}

		// Exit (fail)
		return null;
	}

	/**
	 * Route
	 *
	 * Handle route
	 *
	 * @param route $route The route
	 * @param request $request The request
	 *
	 * @throws exception_domain if failed to find the controller or the model
	 * @throws exception_logic if not received the controller
	 * @throws exception_method if failed to find the method of the controller
	 *
	 * @return string|null Response, if generated
	 */
	public function route(route $route, request $request): ?string
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
			throw new exception_domain("Failed to find the controller: $controller", status::not_implemented->value);
		} else {
			// Not found the controller and $route->controller is empty

			// Exit (fail)
			throw new exception_logic('Not received the controller', status::internal_server_error->value);
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
			throw new exception_domain("Failed to find the model: $model", status::not_implemented->value);
		}

		// Deinitializing name of the model class
		unset($model);

		if ($route->model instanceof model) {
			// Initialized the model

			// Writing the model to the controller
			$route->controller->model = $route->model;
		}

		// Writing the request to the controller
		$route->controller->request = $request;

		if (method_exists($route->controller, $route->method)) {
			// Found the method of the controller

			// Executing method of the controller and exit (success)
			return $route->controller->{$route->method}(...($route->parameters + $request->parameters));
		} else {
			// Not found the method of the controller
		
			// Exit (fail)
			throw new exception_method('Failed to find method of the controller: ' . $route->controller::class . "->$route->method()", status::not_implemented->value);
		}

		// Exit (fail)
		return null;
	}
}
