<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Files of the project
use mirzaev\minimal\model,
	mirzaev\minimal\traits\magic;

// Встроенные библиотеки
use exception;

/**
 * Controller (base)
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
	 * Postfix of file names
	 */
	public const string POSTFIX = '_controller';

	/**
	 * Instance of the model connected in the router
	 */
	protected model $model;

	/**
	 * View template engine instance (twig)
	 */
	protected object $view;

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Write property
	 *
	 * @param string $name Name of the property
	 * @param mixed $value Value of the property
	 *
	 * @return void
	 */
	public function __set(string $name, mixed $value = null): void
	{
		match ($name) {
			'model' => (function () use ($value) {
				if ($this->__isset('model')) throw new exception('Can not reinitialize property: ' . static::class . '::$model', 500);
				else {
					// Property not initialized 

					if (is_object($value)) $this->model = $value;
					else throw new exception('Property "' . static::class . '::view" should store an instance of a model', 500);
				}
			})(),
			'view' => (function () use ($value) {
				if ($this->__isset('view')) throw new exception('Can not reinitialize property: ' . static::class . '::$view', 500);
				else {
					// Property not initialized 

					if (is_object($value)) $this->view = $value;
					else throw new exception('Property "' . static::class . '::view" should store an instance of a view template engine', 500);
				}
			})(),
			default => throw new exception('Property "' . static::class . "::\$$name\" not found", 404)
		};
	}

	/**
	 * Read property
	 *
	 * @param string $name Name of the property
	 *
	 * @return mixed Value of the property
	 */
	public function __get(string $name): mixed
	{
		return match ($name) {
			'model' => $this->model ?? throw new exception('Property "' . static::class . '::$model" is not initialized', 500),
			'view' => $this->view ?? throw new exception('Property "' . static::class . '::$view" is not initialized', 500),
			default => throw new exception('Property "' . static::class . "::\$$name\" not found", 404)
		};
	}
}
