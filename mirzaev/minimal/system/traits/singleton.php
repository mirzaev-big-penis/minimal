<?php

declare(strict_types=1);

namespace mirzaev\minimal\traits;

// Built-in libraries
use exception;

/**
 * Trait of singleton
 *
 * @package mirzaev\minimal\traits
 *
 * @method static initialize() Initialize an instance
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
trait singleton
{
	/**
	 * Constructor (blocked)
	 *
	 * @return void
	 */
	final private function __construct()
	{
		// Initializing the indicator that an instance of static::class has already been initialized
		static $instance = false;

		if ($instance) {
			// An instance of static has already been initialized

			// Exit (fail)
			throw new exception('Has already been initialized an instance of the ' . static::class);
		}

		// Writing the indicator that the instance of static been initialized
		$instance = true;
	}

	/**
	 * Initialize an instance
	 *
	 * Create an instance of static::class, or return an already created static::class instance
	 *
	 * @return static
	 */
	public static function initialize(): static
	{
		// Initialize the buffer of the instance of static::class
		static $instance;

		// Exit (success)
		return $instance ??= new static;
	}

	/**
	 * Clone (blocked)
	 */
	private function __clone()
	{
		// Exit (fail)
		throw new exception('Cloning is inadmissible');
	}

	/**
	 * Sleep (blocked)
	 */
	public function __sleep()
	{
		// Exit (fail)
		throw new exception('Serialization is inadmissible');
	}

	/**
	 * Wake up (blocked)
	 */
	public function __wakeup()
	{
		// Exit (fail)
		throw new exception('Deserialisation is inadmissible');
	}
}
