<?php

declare(strict_types=1);

namespace mirzaev\minimal\traits;

// Built-in libraries
use exception;

/**
 * Trait of magical methods
 *
 * @method void __set(string $name, mixed $value) Write property
 * @method mixed __get(string $name) Read property
 * @method void __unset(string $name) Delete property
 * @method bool __isset(string $name) Check property for initialization
 *
 * @package mirzaev\minimal\traits
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
trait magic
{
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
			default => throw new exception('Property "' . static::class . "::\$$name\" not found", 404)
		};
	}

	/**
	 * Delete property
	 *
	 * @param string $name Name of the property
	 *
	 * @return void
	 */
	public function __unset(string $name): void
	{
		match ($name) {
			default => (function () use ($name) {
				unset($this->{$name});
			})()
		};
	}

	/**
	 * Check property for initialization
	 *
	 * @param string $name Name of the property
	 *
	 * @return bool Is the property initialized?
	 */
	public function __isset(string $name): bool
	{
		return match ($name) {
			default => isset($this->{$name})
		};
	}
}
