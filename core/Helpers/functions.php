<?php

/**
 * Helper function to use @auth and @guest on blade template
 *
 * @return anonymous
 */
function auth(){
	return new class{
		function guard(){
			return new class{
				function guest(){ return !is_logged(); }
				function check(){ return is_logged(); }
			};
		}
	};
}

/**
 * Helper function to use @method on blade template
 *
 * @param string $method
 * @return string
 */
function method_field($method){
	global $app;
	return in_array($method, $app->router->getRequestAccepted()) ? '<input type="hidden" name="_method" value="'.strtoupper($method).'" />' : '';
}

/**
 * Alias from csrf_token for use on blade template
 *
 * @param string $route
 * @return string
 */
function csrf_field($route = ''){
	return csrf_token($route);
}

/**
 * Dump the variable and die
 *
 * @param mixed $var
 * @return void
 */
function dd($var){
	array_map(function($x){
		(new Attla\Dumper)->dump($x);
	}, func_get_args());
	die(1);
}

/**
 * Get a first and last name
 *
 * @param string $name
 * @return string
 */
function name($name){
	return count($p=explode(' ',$name)) > 1 ? $p[0].' '.(strlen($p[1]) > 3 ? $p[1] : end($p)) : $name;
}

/**
 * Get the year in 2018 - 2020 format
 *
 * @return string
 */
function year(){
	if (!isset(config()->year)) return date('Y');
	return ($y = config()->year) && date('Y') > $y ? $y.' - '.date('Y') : $y;
}

/**
 * Get CSRF input
 *
 * @param string $route
 * @return string
 */
function csrf_token($route = ''){
	if ($route) Attla\CSRF::set_csrf($route);
	return Attla\CSRF::get_csrf();
}

/**
 * Get a route URL by name
 *
 * @param string $routeName
 * @param array $params
 * @return string|boolean
 */
function route($routeName = '', $params = []){
	global $app;
	$math = $app->router->getRoute($routeName, $params);
	return $math !== false ? URL.$math : $math;
}

/**
 * Stores application settings
 *
 * @param object $config
 * @return object
 */
function config($config = 0){
	static $conf;
	if ($config) $conf = $config;
	return $conf;
}

/**
 * Get the absolute path by pointing to assets folder
 *
 * @param string $file
 * @return string
 */
function assets($file = ''){
	return URL.'public/'.(is_dir(VPATH.'assets') && strpos($file, 'assets') === false ? 'assets/':'').trim($file, '/');
}

/**
 * Alias from assets
 *
 * @param string $file
 * @return string
 */
function asset($file = ''){
	return assets($file);
}

/**
 * Get the absolute path
 *
 * @param string $location
 * @return string
 */
function uri($location = ''){
	return URL.trim($location, '/');
}

/**
 * Alias from uri
 *
 * @param string $location
 * @return string
 */
function url($location = ''){
	return uri($location);
}

/**
 * Management of global application variables
 *
 * @return mixed
 */
function globals(){
	static $g = [];
	if (!$g){
		$g['user'] = i('Attla\User')->check();
	}
	$args = func_get_args();
	$c = count($args);
	if ($c == 2 && !is_array($args[0])){
		$g[$args[0]] = $args[1];
	}elseif ($c == 2 && is_array($args[0]) || $c == 1 && is_array($args[0])){
		foreach($args[0] as $k => $v) $g[$k] = $v;
	}elseif ($c == 1){
		return isset($g[$args[0]]) ? $g[$args[0]] : false;
	}elseif (!$c){
		return $g;
	}else{
		err('Invalid arguments to set a global variable.');
	}
}

/**
 * Set a global application variable
 *
 * @param string|array $key
 * @param mixed $val
 * @return void
 */
function set_globals($key = '', $val = ''){
	globals($key, $val);
}

/**
 * Set a global application variable
 *
 * @param string|array $key
 * @param mixed $val
 * @return void
 */
function set_global($key = '', $val = ''){
	globals($key, $val);
}

/**
 * Get a global application variable
 *
 * @param string $key
 * @return mixed
 */
function get_globals($key = ''){
	return globals($key);
}

/**
 * Get a global application variable
 *
 * @param string $key
 * @return mixed
 */
function get_global($key = ''){
	return globals($key);
}

/**
 * Includes a file, with access to all global variables and blade template
 *
 * @param string $file
 * @return void
 */
function import($file = ''){
	if($file) new Attla\Render($file, globals(), false);
}

/**
 * Includes the header file
 *
 * @param string $title
 * @param string $description
 * @param string $path
 * @return void
 */
function h($title = '', $description = '', $path = ''){
	new Attla\Render(_resolvePath($path).'header', array_merge([
		'title' => $title,
		'description' => $description
	], globals()), false);
}

/**
 * Includes the footer file
 *
 * @param string $path
 * @return void
 */
function f($path = ''){
	new Attla\Render(_resolvePath($path).'footer', globals(), false);
}

/**
 * Formats the path for file inclusion
 *
 * @param string $path
 * @return string
 */
function _resolvePath($path = ''){
	$path = ltrim($path, '/\\');
	return $path && $path[strlen($path) - 1] != '/' ? $path.'/' : $path;
}

/**
 * Header alias
 *
 * @param string $title
 * @param string $description
 * @param string $path
 * @return void
 */
function get_header($title = '', $description = '', $path = ''){
	h($title, $description, $path);
}

/**
 * Footer alias
 *
 * @param string $path
 * @return void
 */
function get_footer($path = ''){
	f($path);
}

/**
 * Header alias
 *
 * @param string $title
 * @param string $description
 * @param string $path
 * @return void
 */
function page_header($title = '', $description = '', $path = ''){
	h($title, $description, $path);
}

/**
 * Footer alias
 *
 * @param string $path
 * @return void
 */
function page_footer($path = ''){
	f($path);
}

/**
 * Stores and get instances of classes
 *
 * @param string $class
 * @param array $args
 * @return ReflectionClass
 */
function i($class = '', ...$args){
	if(!$class) return;
	static $a = [];
	if(isset($a[$class]))
		return $a[$class];
	return $a[$class] = (new ReflectionClass($class))->newInstanceArgs(isset($args[0]) && is_array($args[0]) ? $args[0] : $args);
}

/**
 * Stores and get instances of classes
 *
 * @param string $class
 * @param array $args
 * @return ReflectionClass
 */
function get_instance($class = '', ...$args){
	return i($class, $args);
}

/**
 * Check if it is an ajax request
 *
 * @return boolean
 */
function is_ajax(){
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/**
 * Check if it is browser
 *
 * @param string $key
 * @return boolean
 */
function is_browser($key = ''){
	return i('Attla\UserAgent')->is_browser($key);
}

/**
 * Get browser name
 *
 * @return string
 */
function browser(){
	return i('Attla\UserAgent')->browser();
}

/**
 * Get the browser version
 *
 * @return string
 */
function browser_version(){
	return i('Attla\UserAgent')->version();
}

/**
 * Check if it is mobile
 *
 * @param string $key
 * @return boolean
 */
function is_mobile($key = ''){
	return i('Attla\UserAgent')->is_mobile($key);
}

/**
 * Get the mobile device
 *
 * @return string
 */
function mobile(){
	return i('Attla\UserAgent')->mobile();
}

/**
 * Check if it is robot
 *
 * @param string $class
 * @return boolean
 */
function is_robot($key = ''){
	return i('Attla\UserAgent')->is_robot($key);
}

/**
 * Get the robot name
 *
 * @return string
 */
function robot(){
	return i('Attla\UserAgent')->robot();
}

/**
 * Get the IP
 *
 * @return string
 */
function ip(){
	return i('Attla\UserAgent')->ip();
}

/**
 * Is this a referral from another site?
 *
 * @return boolean
 */
function is_referral(){
	return i('Attla\UserAgent')->is_referral();
}

/**
 * Get the referrer
 *
 * @return string
 */
function referrer(){
	return i('Attla\UserAgent')->referrer();
}

/**
 * Returns an index of user data
 *
 * @param string $key
 * @return mixed
 */
function user($key = ''){
	return i('Attla\User')->user($key);
}

/**
 * Renew user session
 *
 * @return object
 */
function new_sign(){
	return i('Attla\User')->new_sign();
}

/**
 * Checks if the user is logged in
 *
 * @return boolean
 */
function is_logged(){
	return i('Attla\User')->is_logged();
}

/**
 * Executes a query, if get only 1 result, it will return it
 *
 * @param string $query
 * @param array $bindParams
 * @return array|boolean
 */
function query($query = '', $bindParams = []){
	return i('Attla\Database')->query($query, $bindParams);
}

/**
 * Executes a query, forces the return of an array list
 *
 * @param string $query
 * @param array $bindParams
 * @return array|boolean
 */
function query_list($query = '', $bindParams = []){
	$query = query($query, $bindParams);
	return is_array($query) && isset($query['id']) ? [$query] : $query;
}

/**
 * Checks if a record exists in the database
 *
 * @param string $table
 * @param string $key
 * @param string $value
 * @return boolean
 */
function exist($table, $key, $value){
	return (bool) i('Attla\Database')->find($table,[$key => $value]);
}

/**
 * Randomize positions of an array
 *
 * @param array $array
 * @return array
 */
function array_random($array = []){
	if(!is_array($array))  $array = (array) $array;
	if(!$array)  return [];
	$keys = array_keys($array);
	shuffle($keys);
	$return = [];
	foreach ($keys as $index) $return[$index] = $array[$index];
	return $return;
}

/**
 * Get a random value from an array
 *
 * @param array $array
 * @return mixed
 */
function array_random_value($array){
	return isset($array[0]) ? $array[mt_rand(0, count($array)-1)] : $array[array_rand($array)];
}

/**
 * Checks if a value is empty
 *
 * @param string $val
 * @return boolean
 */
function is_empty($val){
	return strlen(trim(preg_replace('/\xc2\xa0/', ' ', $val))) == 0 ? true : false;
}

/**
 * Check if it is a valid email
 *
 * @param string $email
 * @return boolean
 */
function is_email($email){
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? true : false;
}

/**
 * Check if it is a valid username
 *
 * @param string $username
 * @return boolean
 */
function is_username($username){
	return preg_match('/^[a-z\d_.-]{3,20}$/i', $username) ? true : false;
}

/**
 * Check if it is a valid URL
 *
 * @param string $url
 * @return boolean
 */
function is_url($url){
	return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url) ? true : false;
}

/**
 * Check if it is a valid full name
 *
 * @param string $name
 * @return boolean
 */
function is_name($name){
	return (preg_match("/^([\\p{L}'-.]+ [\\p{L}'-.]+)(.*)$/ui", $name)) ? true : false;
}

/**
 * Serialize data if needed
 *
 * @param string $data
 * @return string
 */
function maybe_serialize($data){
	return (is_array($data) || is_object($data)) && !is_serialized($data) ? @serialize($data) : $data;
}

/**
 * Unserialize value only if it was serialized
 *
 * @param string $data
 * @return mixed
 */
function maybe_unserialize($data){
	return is_serialized($data) ? @unserialize($data) : $data;
}

/**
 * Check value to find if it was serialized
 *
 * @param string $data
 * @return boolean
 */
function is_serialized($data){
	if(!is_string($data)) return false;
	$data = trim($data);
	if('N;' == $data) return true;
	if(!preg_match('/^([adObis]):/', $data, $match)) return false;
	switch ($match[1]){
		case 'a':case 'O':case 's':
			if (preg_match("/^{$b[1]}:[0-9]+:.*[;}]\$/s", $data)) return true;
			break;
		case 'b':case 'i':case 'd':
			if (preg_match("/^{$b[1]}:[0-9.E-]+;\$/", $data)) return true;
			break;
	}
	return false;
}

/**
 * Check if it is a valid base64
 *
 * @param string $data
 * @return boolean
 */
function is_base64($data){
	return (base64_encode(base64_decode($data)) === $data);
}

/**
 * Check if it is a valid json
 *
 * @param string $data
 * @return boolean
 */
function is_json($data){
	json_decode($data);
	return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Create a cookie
 *
 * @param string $name
 * @param string $val
 * @param integer $time
 * @return boolean
 */
function cookie($name = '', $val = '', $time = 1){
	if(isset($_COOKIE[$n])) unset($_COOKIE[$n]);
	(!headers_sent()) ? setcookie($name, $val, $time, "/", $_SERVER['HTTP_HOST'], false, true) : err("Can't set cookie ($n), headers was sended.");
	return isset($_COOKIE[$n]);
}

/**
 * Creates a redirect
 *
 * @param string $url
 * @return void
 */
function r($url = ''){
	global $app;
	$url = trim($url,'/');
	if (get_global('uri') == $url && $app->router->getMethod() == "GET") return;
	if (!preg_match('#^(http|https)?://#i', $url)) $url = URL.$url;
	if (headers_sent()) die("<script>window.location.href='$url';</script><noscript><meta http-equiv=refresh content=0;URL='$url'></noscript>");
	else header("Location: ".$url, true, 302);
	die;
}

/**
 * Alias from redirect
 *
 * @param string $url
 * @return void
 */
function redirect($url = ''){
	r($url);
}

/**
 * Convert an array or object to json and display it
 *
 * @param mixed $data
 * @param integer $code
 * @return void
 */
function json($data = '', $code = 200){
	if (!headers_sent()){
		header('Cache-Control: no-cache, must-revalidate');
		header('Content-Type: application/json');
	}
	set_status_header($code);
	die(json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Checks if an index exists in the global $_GET and returns it
 *
 * @param string $key
 * @return mixed
 */
function get($key = ''){
	return isset($_GET[$key]) ? $_GET[$key] : false;
}

/**
 * Checks if an index exists in the global $_POST and returns it
 *
 * @param string $key
 * @return mixed
 */
function post($key = ''){
	return isset($_POST[$key]) ? $_POST[$key] : false;
}

/**
 * Checks if an index exists in the global $_REQUEST and returns it
 *
 * @param string $key
 * @return mixed
 */
function input($key = ''){
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : false;
}

/**
 * Handles application errors
 *
 * @return void
 */
function err(){
	$exception = i('Attla\Exceptions');
	$args = func_get_args();
	switch (count($args)){
		case 4:case 5:
			$exception->show_php_error($args[0], $args[1], $args[2], $args[3]);
			break;
		case 3:
			$exception->show_error($args[0], $args[1], $args[2]);
			die;
			break;
		case 2:
			$exception->show_error($args[0], $args[1], 200);
			die;
			break;
		case 1:
			if(!is_string($args[0])) $exception->show_restrict_page();
			else $exception->show_error('Internal Error', $args[0]);
			die;
			break;
		default:
			$exception->show_404();
			die;
			break;
	}
}

/**
 * Set status header
 *
 * @param integer $code
 * @param string $title
 * @return void
 */
function set_status_header($code = 200, $title = ''){
	if(headers_sent()) return;
	$status = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => "I'm a teapot",
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		451 => 'Unavailable For Legal Reasons',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		510 => 'Not Extended',
		511 => 'Network Authentication Required'
	];
	if ($code == '' || !is_numeric($code)) err('Status codes must be numeric');
	if (isset($status[$code]) AND $title == '') $title = $status[$code];
	if ($title == '') err('No status text available.');

	$protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : false;
	if (substr(php_sapi_name(), 0, 3) == 'cgi') header("Status: $code $title", true);
	elseif ($protocol == 'HTTP/1.1' OR $protocol == 'HTTP/1.0') header("$protocol $code $title", true, $code);
	else header("HTTP/1.1 $code $title", true, $code);
}