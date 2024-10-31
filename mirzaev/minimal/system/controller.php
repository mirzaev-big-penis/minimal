<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Files of the project
use mirzaev\minimal\model,
	mirzaev\minimal\core,
	mirzaev\minimal\traits\magic;

// Build-in libraries
use exception;

/**
 * Controller
 *
 * @var core $core An instance of the core
 * @var model $model An instance of the model connected in the core
 * @var view $view View template engine instance (twig)
 * @var core $core An instance of the core
 * @var core $core An instance of the core
 *
 * @method self __construct(core $core) Constructor
 *
 * @package mirzaev\minimal
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
	 * Model
	 *
	 * @var model $model An instance of the model connected in the core
	 */
	public model $model {
		// Write
		set (model $model) {
				$this->model ??= $model;
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
	 * @return self
	 */
	public function __construct(core $core) {
		// Writing the core into the property
		$this->core = $core;
	}
}
