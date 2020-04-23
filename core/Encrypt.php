<?php

namespace Attla;

class Encrypt
{
	/**
	 * This defines the accepted method of transforming array and objects into strings
	 * switch to serialize if you don't want to use a json method
	 *
	 * @var string
	 */
	private static $string_mode = 'json';

	/**
	 * Base64 characters
	 *
	 * @var string
	 */
	private static $from = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/';

	/**
	 * Base64 replacement characters, used only if config()->encrypt_replacement does not exist
	 *
	 * @var string
	 */
	private static $to = 'pHt1r5j0sCe34NDzqQOV8kBKG9XmwvfiAIdLZ2aRlxSYcuPWnybUgETJ6MhFo7.-';

	/**
	 * Encryption secret key, used only if config()->encrypt_key does not exist
	 *
	 * @var string
	 */
	private static $encrypt_key = 'より古いバージョンから対応しているようなので を使う 未知の形式';

	/**
	 * Encrypt a string following a salt, salt should not be passed if it is not a hash
	 *
	 * @param string $password
	 * @param string $salt
	 * @return string
	 */
	public static function hash($password, $salt = ''){
		$salt = !$salt ? substr(md5(uniqid(mt_rand(), true)), 0, 31%strlen($password)) : (($r = strlen($salt)%40) && $r%2 ? substr($salt, 0, $r) : substr($salt, -$r));
		return ($r = strlen($salt)%2 ? $salt:'').sha1($password.$salt).($r ? '':$salt);
	}

	/**
	 * Compare an unencrypted password with an encrypted password
	 *
	 * @param string $unencrypted
	 * @param string $encrypted
	 * @return string
	 */
	public static function hash_equals($unencrypted, $encrypted){
		return hash_equals($encrypted, self::hash($unencrypted, $encrypted));
	}

	/**
	 * Encrypt and decrypt a string with a secret key
	 *
	 * @param string $str
	 * @param boolean $type
	 * @param string $secret_key
	 * @return string
	 */
	private static function en($str = '', $type = true, $secret_key = ''){
		if(!$str || !$secret_key) return '';
		$return = '';
		if(!$type) $str = base64_decode(strtr($str, isset(config()->encrypt_replacement) ? config()->encrypt_replacement : self::$to, self::$from));
		$a = strlen($str)-1;
		$b = strlen($secret_key);
		do{
			$return .= ($str[$a] ^ $secret_key[$a%$b]);
		}while ($a--);
		$return = strrev($return);
		return $type ? rtrim(strtr(base64_encode($return), self::$from, isset(config()->encrypt_replacement) ? config()->encrypt_replacement : self::$to), '=') : $return;
	}

	/**
	 * Encrypt an md5 in bytes of a string
	 *
	 * @param string $str
	 * @return string
	 */
	private static function md5($str){
		return self::en(md5($str, true), true, isset(config()->encrypt_key) ? config()->encrypt_key : self::$encrypt_key);
	}

	/**
	 * Convert array and objects to strings
	 *
	 * @param array|object $array
	 * @return string
	 */
	private static function toText($array){
		return self::$string_mode == 'serialize' ? serialize($array) : json_encode($array);
	}

	/**
	 * Encrypt a anyting with secret key
	 *
	 * @param mixed $str
	 * @param string $key
	 * @return string
	 */
	public static function encode($str, $key = ''){
		return self::en(is_array($str) || is_object($str)? self::toText(is_array($str) ? array_random($str) : $str) : $str, true, $key ? $key : (isset(config()->encrypt_key) ? config()->encrypt_key : self::$encrypt_key));
	}

	/**
	 * Decrypt a anyting with secret key
	 *
	 * @param mixed $str
	 * @param string $key
	 * @return mixed
	 */
	public static function decode($str, $key = ''){
		if ($return=self::en($str, false, $key ? $key : (isset(config()->encrypt_key) ? config()->encrypt_key : self::$encrypt_key))){
			if(is_json($return)) $return = json_decode($return);
			if(is_serialized($return)) $return = (object) unserialize($return);
		}
		return $return;
	}

	/**
	 * Modified implementation of a JWT
	 *
	 * @param array $header
	 * @param array $payload
	 * @return string
	 */
	public static function jwt($header, $payload){
		if(!isset($header['k'])) $header['k'] = self::md5(uniqid(mt_rand(), true));
		$payload = self::encode($payload, $header['k']);
		$header = self::encode($header);
		return $header.'_'.$payload.'_'.self::md5($header.$payload);
	}

	/**
	 * Check if JWT is valid and returns payload
	 *
	 * @param string $jwt
	 * @return mixed
	 */
	public static function is_jwt($jwt){
		if (!$jwt || !is_string($jwt)) return false;
		$jwt = explode('_',$jwt);
		if (count($jwt) !=3 || $jwt[2] != self::md5($jwt[0].$jwt[1])) return false;
		if ($header = self::decode($jwt[0]) and $payload = self::decode($jwt[1], $header->k) and is_object($header) && is_object($payload)){
			if (isset($header->exp) && time() > $header->exp) return false;
			if (isset($header->iss) && $_SERVER['HTTP_HOST'] != $header->iss) return false;
			if (isset($header->bwr) && browser().substr(browser_version(), 0, 2) != $header->bwr) return false;
			if (isset($header->ip) && ip() != $header->ip) return false;
			return $payload;
		}
		return false;
	}

	/**
	 * Help function to create a JWT
	 *
	 * @param array $data
	 * @param integer $exp
	 * @return string
	 */
	public static function sign($data, $exp = 1800){
		return self::jwt([
			'exp' => time() > $exp ? time() + $exp : $exp,
			'iss' => $_SERVER['HTTP_HOST'],
			'bwr' => browser().substr(browser_version(), 0, 2),
			'ip' => ip()
		], $data);
	}
}