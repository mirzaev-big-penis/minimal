<?php

declare(strict_types=1);

namespace mirzaev\minimal;

// Files of the project
use	mirzaev\minimal\traits\magic;

// Built-in libraries
use exception;

/**
 * Model (base)
 *
 * @package mirzaev\minimal
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class model
{
	use magic;

	/**
	 * Postfix of file names
	 */
	public const string POSTFIX = '_model';

	/**
	 * Constructor
	 */
	public function __construct() {}
}
