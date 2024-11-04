<?php

declare(strict_types=1);

namespace mirzaev\minimal\http\enumerations;

/**
 * Protocol
 *
 * Versions of HTTP
 *
 * @package mirzaev\minimal\http\enumerations
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
enum protocol: string
{
	case HTTP_3 = 'HTTP/3';
	case HTTP_2 = 'HTTP/2';
	case HTTP_1_1 = 'HTTP/1.1';
	case HTTP_1_0 = 'hTTP/1.0';
	case HTTP_0_9 = 'HTTP/0.9';
}
