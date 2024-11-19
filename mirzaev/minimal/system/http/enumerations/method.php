<?php

declare(strict_types=1);

namespace mirzaev\minimal\http\enumerations;

/**
 * Method
 *
 * Methods of HTTP request
 *
 * @package mirzaev\minimal\http\enumerations
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
enum method: string
{
	case post = 'POST';
	case get = 'GET';
	case put = 'PUT';
	case delete = 'DELETE';
	case patch = 'PATCH';
	case head = 'HEAD';
	case options = 'OPTIONS';
	case connect = 'CONNECT';
	case trace = 'TRACE';

	/**
	 * Body
	 *
	 * @return bool Request with this method may has body?
	 */
	public function body(): bool
	{
		// Exit (success)
		return match ($this) {
			self::post, self::put, self::delete, self::patch => true,
			default => false
		};
	}
}
