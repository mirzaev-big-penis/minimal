<?php

declare(strict_types=1);

namespace mirzaev\minimal\http\enumerations;

// Built-in libraries
use DomainException as exception_domain;

/**
 * Status
 *
 * Status codes and status texts of HTTP response
 *
 * @package mirzaev\minimal\http\enumerations
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
enum status: int
{
	// 1XX
	
	case continue = 100;
	case switching_protocols = 101;
	case processing = 102;
	case early_hints = 103;
	case among_us = 112;

	// 2XX

	case ok = 200; // ok
	case created = 201;
	case accepted = 202;
	case non_authoritative_information = 203;
	case no_content = 204;
	case reset_content = 205;
	case partial_content = 206;
	case multi_status = 207;
	case already_reported = 208;
	case im_used = 226; // bruh

	// 3XX

	case multiple_choises = 300;
	case moved_permanently = 301;
	case found = 302; // Previously "Moved temporarily"
	case see_other = 303;
	case not_modified = 304;
	case use_proxy = 305;
	case switch_proxy = 306;
	case temporary_redirect = 307;
	case permanent_redirect = 308;

	// 4XX

	case bad_request = 400;
	case unauthorized = 401;
	case payment_required = 402; // Are you fucking commerce?
	case forbidden = 403;
	case not_found = 404; // our celebrity
	case method_not_allowed = 405;
	case not_acceptable = 406;
	case proxy_authentication_required = 407;
	case request_timeout = 408;
	case conflict = 409;
	case gone = 410;
	case length_required = 411;
	case precondition_failed = 412;
	case payload_too_large = 413;
	case uri_too_long = 414;
	case unsupported_media_type = 415;
	case range_not_satisfiable = 416;
	case expectation_failed = 417;
	case i_am_a_teapot = 418;
	case misdirected_request = 421;
	case unprocessable_content = 422;
	case locked = 423;
	case failed_dependency = 424;
	case too_early = 425;
	case upgrade_required = 426;
	case precondition_required = 428;
	case too_many_requests = 429;
	case request_header_fields_too_large = 431;
	case unavailable_for_legal_reasons = 451; // @see self::failed_state
	case bruh = 441;

	// 5XX

	case internal_server_error = 500;
	case not_implemented = 501;
	case bad_gateway = 502;
	case service_unawaiable = 503;
	case gateway_timeout = 504;
	case http_version_not_supported = 505;
	case variant_also_negotiates = 506;
	case insufficient_storage = 507;
	case loop_detected = 508;
	case not_extended = 510;
	case network_authentication_required = 511;

	// 9XX

	case failed_state = 911;

	/**
	 * Label
	 *
	 * The result will be in ucwords() format - first character uppercase, rest lowercase
	 *
	 * You might want to do strtoupper() - convert all characters to uppercase
	 * "HTTP/2 200 Ok" after strtoupper() will be "HTTP/2 200 OK"
	 *
	 * It is common for "OK" to have both characters uppercase, 
	 * and for the other status texts only the first letter of each word uppercase. 
	 * This is universalized here, so the result will be "Ok" instead of "OK".
	 *
	 * If you want to get "OK" without using strtoupper(), 
	 * just use the literal "OK" instead of self::ok->label()
	 *
	 * The uppercase letter on each word makes it easier and faster to read the status text, 
	 * even if it violates the grammar rules we are accustomed to
	 *
	 * I also indignantly conducted a test and tried to use "Early hints" instead of "Early Hints", 
	 * as well as "Already reported" instead of "Already Reported". 
	 * The result of my tests was that the readability of such status texts is greatly reduced.
	 *
	 * Also note the following:
	 * 1. "Non-Authoritative Information" -> "Non Authoritative Information"
	 * 2. "Multi-Status" -> "Multi Status"
	 * 3. "IM Used" -> "I Am Used"
	 * 4. "I`m a teapot" -> "I Am A Teapot" (Initially, i wanted to leave it as in honor of tradition, 
	 * but i decided that this is, first of all, one of our current and working status codes to this day, 
	 * so it should also be universalized.
	 * 5. "URI" retains its case because it is an abbreviation.
	 * 6. "HTTP" retaints its case because it is an abbreviation.
	 *
	 * If you do not like my changes, just fork MINIMAL and edit this file. 
	 * You will be able to get updates without any problems, which probably will not touch this file.
	 *
	 * Or you can write a BASH/FISH script with the creation of a link to your version of the file, 
	 * which will be executed after `composer install`. Of course, you should not do this, 
	 * but this is a completely working solution that is unlikely to break anything 
	 * and is completely automatic and portable.
	 *
	 * @throws exception_domain if failed to recognize status
	 *
	 * @return string Label
	 */
	public function label(): string
	{
		// Exit (success)
		return match ($this) {
			// 1XX

			self::continue => 'Continue',
			self::switching_protocols => 'Switching Protocols',
			self::processing => 'Processing',
			self::early_hints => 'Early Hints',
			self::among_us => 'Among Us',

			// 2XX

			self::ok => 'Ok',
			self::created => 'Created',
			self::accepted => 'Accepted',
			self::non_authoritative_information => 'Non Authoritative Information',
			self::no_content => 'No Content',
			self::reset_content => 'Reset Content',
			self::partial_content => 'Partial Content',
			self::multi_status => 'Multi Status',
			self::already_reported => 'Already Reported',
			self::im_used => 'I Am Used',

			// 3XX

			self::multiple_choises => 'Multiple Choices',
			self::moved_permanently => 'Moved Permanently',
			self::found => 'Found', // Previously "Moved Temporarily"
			self::see_other => 'See Other',
			self::not_modified => 'Not Modified',
			self::use_proxy => 'Use Proxy',
			self::switch_proxy => 'Switch Proxy',
			self::temporary_redirect => 'Temporary Redirect',
			self::permanent_redirect => 'Permanent Redirect',

			// 4XX

			self::bad_request => 'Bad Request',
			self::unauthorized => 'Unauthorized',
			self::payment_required => 'Payment Required', // do not make me angry
			self::forbidden => 'Forbidden',
			self::not_found => 'Not Found',
			self::method_not_allowed => 'Method Not Allowed',
			self::not_acceptable => 'Not Acceeptable',
			self::proxy_authentication_required => 'Proxy Authentication Required',
			self::request_timeout => 'Request Timeout',
			self::conflict => 'Conflict',
			self::gone => 'Gone',
			self::length_required => 'Length Reuired',
			self::precondition_failed => 'Precondition Failed',
			self::payload_too_large => 'Payload Too Large',
			self::uri_too_long => 'URI Too Long',
			self::unsupported_media_type => 'Unsupported Media Type',
			self::range_not_satisfiable => 'Range Not Satisfiable',
			self::expectation_failed => 'Exception Failed',
			self::i_am_a_teapot => 'I Am A Teapot',
			self::misdirected_request => 'Misdirected Request',
			self::unprocessable_content => 'Unprocessable Content',
			self::locked => 'Locked',
			self::failed_dependency => 'Failed Dependency',
			self::too_early => 'Too Early',
			self::upgrade_required => 'Upgrade Required',
			self::precondition_required => 'Precondition Required',
			self::too_many_requests => 'Too Many Requests',
			self::request_header_fields_too_large => 'Request Header Fields Too Large',
			self::unavailable_for_legal_reasons => 'Unavaiable For Legal Reasons', // Fucking disgrease.
			self::bruh => 'Bruh',

			// 5XX

			self::internal_server_error => 'Internal Server Error',
			self::not_implemented => 'Not Implemented',
			self::bad_gateway => 'Bad Gateway',
			self::service_unawaiable => 'Service Unawaiable',
			self::gateway_timeout => 'Gateway Timeout',
			self::http_version_not_supported => 'HTTP Version Not Supported',
			self::variant_also_negotiates => 'Variant Also Negotiates',
			self::insufficient_storage => 'Insufficient Storage',
			self::loop_detected => 'Loop Detected',
			self::not_extended => 'Not Extended',
			self::network_authentication_required => 'Network Authentication Required',

			// 9XX

			self::failed_state => 'Failed State',

			default => throw new exception_domain('Failed to recognize status', self::not_found->value)
		};
	}
}
