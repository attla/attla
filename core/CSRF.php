<?php

namespace Attla;

class CSRF
{
	/**
	 * Stores the cookie name
	 *
	 * @var string
	 */
	private static $name = '';

	/**
	 * Stores token hash
	 *
	 * @var string
	 */
	private static $token = '';

	/**
	 * Stores the path
	 *
	 * @var string
	 */
	private static $path = '';

	/**
	 * Get the token name
	 *
	 * @param string $new
	 * @return string
	 */
	private static function name($new = ''){
		return !$new && self::$name ? self::$name : self::$name = self::time_token();
	}

	/**
	 * Get the path concatenated with the browser version
	 *
	 * @param string $new
	 * @return string
	 */
	private static function path($new = ''){
		return !$new && self::$path ? self::$path : self::$path = ($new ? $new : (isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI']))).browser().substr(browser_version(), 0, 2);
	}

	/**
	 * Get the token hash
	 *
	 * @param string $new
	 * @return string
	 */
	private static function token($new = ''){
		return !$new && self::$token != null ? self::$token : self::$token = Encrypt::hash(self::path($new));
	}

	/**
	 * Set the CSRF cookie
	 *
	 * @param string $new
	 * @return void
	 */
	public static function set_csrf($new = ''){
		setcookie(self::name($new), self::token($new), time() + 599, $new ? $new : (isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'])), $_SERVER['HTTP_HOST'], false, true);
	}

	/**
	 * Checks the CSRF token in the request
	 *
	 * @param string $new
	 * @return boolean
	 */
	public static function verify($new = ''){
		if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
			self::set_csrf($new);
			return false;
		}
		$r = (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']) < 0  || preg_match("/curl|libcurl/",$_SERVER['HTTP_USER_AGENT']) || !isset($_POST[self::name()], $_COOKIE[self::name()]) || $_POST[self::name()] != $_COOKIE[self::name()] || !Encrypt::hash_equals(self::path($new), $_POST[self::name()])) ? false : true;
		unset($_POST[self::name()], $_COOKIE[self::name()]);
		self::set_csrf($new ? $new : 1);
		return $r;
	}

	/**
	 * Returns a random number of spaces
	 *
	 * @return string
	 */
	private static function nl(){
		return str_repeat(" ", abs(mt_rand(-128, 127)));
	}

	/**
	 * Get CSRF input
	 *
	 * @return string
	 */
	public static function get_csrf(){
		if(!self::$name) self::set_csrf();
		return self::nl().'<input'.self::nl().'type="hidden"'.self::nl().'name="'.self::name().'"'.self::nl().'value="'.self::token().'"'.self::nl().'/>'.self::nl();
	}

	/**
	 * Get a time-based token
	 *
	 * @param string $str
	 * @return string
	 */
	private static function time_token($str = 'h'){
		return substr(md5(dechex(substr(date('dmyHi',strtotime('+1'.($str=='m'?0:'').' '.str_replace(['m','h','d'], ['min','hour','day'], $str))), 0, $str == 'm' ? 9 : ($str == 'h' ? 8 : 6)))), 0, 10);
	}
}