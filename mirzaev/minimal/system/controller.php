<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Files of the project
use mirzaev\minimal\model,
	mirzaev\minimal\core,
	mirzaev\minimal\traits\magic,
	mirzaev\minimal\http\request,
	mirzaev\minimal\http\enumerations\status;

// Built-in libraries
use exception,
	RuntimeException as exception_runtime;

/**
 * Controller
 *
 * @package mirzaev\minimal
 *
 * @var core $core An instance of the core
 * @var request $request Request
 * @var model $model An instance of the model connected in the core
 * @var view $view View template engine instance (twig)
 *
 * @method void __construct(core $core) Constructor
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class controller
{
	use magic;

	/**
	 * Core
	 *
	 * @var core $core An instance of the core
	 */
	public core $core {		
		// Read
		get => $this->core;
	}

	/**
	 * Request
	 *
	 * @var request $request Request
	 */
	public request $request {		
		// Read
		get => $this->request;
	}

	/**
	 * Model
	 *
	 * @throws exception_runtime if reinitialize the property
	 * @throws exception_runtime if an attempt to write null
	 *
	 * @var model $model An instance of the model connected in the core
	 */
	public ?model $model = null {
		// Write
		set (model|null $model) {
			if (isset($this->{__PROPERTY__})) {
				// The property is already initialized

				// Exit (fail)
				throw new exception_runtime('The property is already initialized: ' . __PROPERTY__, status::internal_server_error->value);
			}

			if ($model instanceof model) {
				// Validated model

				// Writing
				$this->model = $model;
			} else {
				// Not validated model
	
				// Exit (fail)
				throw new exception_runtime('The property must be an instance of model', status::internal_server_error->value);
			}
		}

		// Read
		get => $this->model;
	}

	/**
	 * View
	 *
	 * @var view $view View template engine instance (twig)
	 */
	public object $view {
		// Write
		set (object $view) {
				$this->view ??= $view;
		}

		// Read
		get => $this->view;
	}

	/**
	 * Constructor
	 *
	 * @param core $core The instance of the core
	 *
	 * @return void
	 */
	public function __construct(core $core) {
		// Writing the core into the property
		$this->core = $core;
	}
}
