<?php

namespace Attla;

class Router
{
	/**
	 * This array defines the accepted methods and stores their respective routes
	 *
	 * @var array
	 */
	protected $routes = [
		'GET' => [],
		'POST' => [],
		'PUT' => [],
		'PATCH' => [],
		'DELETE' => []
	];

	/**
	 * Array that stores route callables
	 *
	 * @var array
	 */
    protected $callables = [];

	/**
	 * Array that stores route regex
	 *
	 * @var array
	 */
    protected $routeRegexs = [];

	/**
	 * Identifies global route keys
	 *
	 * @var array
	 */
	public $globalKeys = [
		'GLOBAL', 'GLOBALS', 'REQUEST'
	];

	/**
	 * Identifies unique keys to ignore
	 *
	 * @var array
	 */
	protected $excludeKeys = [
		'index',
		'auth_login',
		'auth_not_logged',
		'exclude_routes',
	];

	/**
	 * Identifies unique group keys to ignore
	 *
	 * @var array
	 */
	protected $excludeGroupKeys = [
		'index'
	];

	/**
	 * Last route traveled
	 *
	 * @var string
	 */
	protected $lastRoute = '';

	/**
	 * Last method used by a route
	 *
	 * @var string
	 */
	protected $lastRouteMethod = '';

	/**
	 * Determines whether the last route is global
	 *
	 * @var boolean
	 */
	protected $lastRouteIsGlobal = false;

	/**
	 * Stores route group prefix
	 *
	 * @var string
	 */
	protected $routePrefix = '';

	/**
	 * Route nomenclature array
	 *
	 * @var array
	 */
	protected $routeNames = [];

	/**
	 * URI research protocol
	 *
	 * @var string
	 */
	public $uriProtocol = '';

	/**
	 * Routes ignored by the application
	 *
	 * @var array
	 */
	public $excludeRoutes = [];

	/**
	 * Default route if no route is found
	 *
	 * @var string
	 */
	public $index = '';

	/**
	 * Stores the current uri
	 *
	 * @var string
	 */
	public $uri = '';

	/**
	 * Stores route variables
	 *
	 * @var array
	 */
	public $params = [];

	/**
	 * Constructor
	 *
	 * Defines the default settings and runs the build routine
	 *
	 * @param string $protocol
	 * @param string $index
	 * @param array $excludeRoutes
	 */
	public function __construct($protocol = 'AUTO', $index = 'index', $excludeRoutes = []){
		$this->uriProtocol = $protocol;
		$this->index = $index;
		$this->excludeRoutes = $excludeRoutes;

		self::fetchUri();
		self::excludeRoutes();
		self::setRoutes();
	}

	/**
	 * Fetch the URI and define globally
	 *
	 * @return void
	 */
	private function fetchUri(){
		switch ($this->uriProtocol){
			case 'AUTO':case 'REQUEST_URI':
				if (($uri = self::detectUri()) === false){
					$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
					if (trim($path, '/') != '' && $path != "/".pathinfo(__FILE__, PATHINFO_BASENAME)){
						$uri = $path;
						break;
					}
					$path = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
					if (trim($path, '/') != ''){
						$uri = $path;
						break;
					}
					if (is_array($_GET) AND count($_GET) == 1 AND trim(key($_GET), '/') != ''){
						$uri = key($_GET);
						break;
					}
				}
				break;
			default:
				$uri = isset($_SERVER[$this->uriProtocol]) ? $_SERVER[$this->uriProtocol] : @getenv($this->uriProtocol);
				break;
		}
		$this->uri = ($uri = self::filterUri($uri)) == '' ? $this->index : strtolower($uri);
		set_global('uri', $this->uri);
	}

	/**
	 * Detects the URI and returns the most likely value
	 *
	 * @return string
	 */
	private function detectUri(){
		if (!isset($_SERVER['REQUEST_URI'],$_SERVER['SCRIPT_NAME'])) return false;
		$u = $_SERVER['REQUEST_URI'];
		if (strpos($u,$_SERVER['SCRIPT_NAME']) === 0) $u = substr($u,strlen($_SERVER['SCRIPT_NAME']));
		elseif (strpos($u,dirname($_SERVER['SCRIPT_NAME'])) === 0) $u = substr($u,strlen(dirname($_SERVER['SCRIPT_NAME'])));
		if (strncmp($u,'?/',2) === 0) $u = substr($u,2);
		return $u;
	}

	/**
	 * Filters the URI and escapes unwanted characters
	 *
	 * @param string $uri
	 * @return string
	 */
	private function filterUri($uri){
		return trim(preg_replace([
			'/%([a-fA-F0-9])+|[^\w\.\/_-]/',
			"#//+#", '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\xC2\xA0]+/S',
			'/%0[0-8bcef]/', '/%1[0-9a-f]/'
		], [
			'',
			'/'
		], str_replace([
			'%20',
			'+',
			' '
		], '-', explode('?', $uri)[0])), '/');
	}

	/**
	 * Traverses the excluded routes, if matched it calls page 404
	 *
	 * @return void
	 */
	private function excludeRoutes(){
		foreach ($this->excludeRoutes as $i){
			if(preg_match('@^'.$i.'\/@', $this->uri.'/', $paramValues)) err();
		}
	}

	/**
	 * Get the route methods accepted by the application
	 *
	 * @return array
	 */
 	public function getRequestAccepted(){
		return array_keys($this->routes);
	}

	/**
	 * Defines routes from configuration file
	 *
	 * @return void
	 */
	private function setRoutes(){
		if (!isset(config()->routes)) return;
		foreach(config()->routes as $method => $methods){
			if (!in_array($method, array_merge(self::getRequestAccepted(), $this->globalKeys, $this->excludeKeys))){
				foreach($methods as $groupMethod => $routes){
					if (!in_array($groupMethod, $this->excludeGroupKeys))
						self::parseAccepteds($groupMethod, $routes, $method); // method here is the group name
				}
				continue;
			}
			if (in_array($method, array_merge(self::getRequestAccepted(), $this->globalKeys)))
				self::parseAccepteds($method, $methods);
		}
	}

	/**
	 * Equivalent to in_array but searches recursively within child arrays
	 *
	 * @param string $value
	 * @param array $array
	 * @param boolean $case_insensitive
	 * @return boolean
	 */
	private function deepInArray($value, $array, $case_insensitive = false){
		foreach ($array as $item){
			$ret = is_array($item) ? self::deepInArray($value, $item, $case_insensitive) : (($case_insensitive ? strtolower($item) : $item) == $value);
			if ($ret)
				return $ret;
		}
		return false;
	}

	/**
	 * Built-in function to define routes from a route object
	 *
	 * @param string $method
	 * @param array $accepteds
	 * @param string $group
	 * @return void
	 */
	private function parseAccepteds($method = 'GET', $accepteds = [], $group = ''){
		if ($group){
			if ($group[strlen($group)-1] != '/')
				$group = $group.'/';
			$trim_group = trim($group, '/');
			$group_val = $group.trim(config()->routes->{$trim_group}->index, '/');
			if (!self::deepInArray($group_val, $this->routes['GET'])){
				self::setRoute('POST', [$trim_group => $group_val]);
				self::setRoute('GET', [$trim_group => $group_val]);
			}
		}
		foreach ($accepteds as $name => $route){
			self::setRoute($method, $group.ltrim($route, '/'));
			if (!is_numeric($name))
				self::setName($name);
		}
	}

	/**
	 * Define a route
	 *
	 * @param string $method
	 * @param string|array $route
	 * @param mixed $callable
	 * @return void
	 */
	public function setRoute($method, $route, $callable = false){
		$method = strtoupper($method);
		if (is_string($route))
			$route = trim($route, '/');

		if (!$route) return;

		if ($this->routePrefix)
			$route = $this->routePrefix.$route;

		if(in_array($method, $this->globalKeys)){
			foreach(self::getRequestAccepted() as $acceptedMethod){
				$this->routes[$acceptedMethod][] = $route;
				if ($callable) $this->callables[$acceptedMethod][$route] = $callable;
			}
			$this->lastRouteMethod = 'GET';
			$this->lastRouteIsGlobal = true;
		}else{
			$this->routes[$method][] = $route;
			if ($callable) $this->callables[$method][$route] = $callable;
			$this->lastRouteMethod = $method;
			$this->lastRouteIsGlobal = false;
		}
	}

	/**
	 * Set prefix for route group
	 *
	 * @param string $prefix
	 * @param Closure $func
	 * @return void
	 */
	public function group($prefix, $func){
		$prefix = ltrim($prefix, '/');
		if ($prefix[strlen($prefix)-1] != '/')
				$prefix = $prefix.'/';
		$this->routePrefix = $prefix;
		if ($func instanceof \Closure){
			$func();
		}else{
			err('Group method callable must be a Closure.');
		}
		$this->routePrefix = '';
	}

	/**
	 * Define the regex of a newly created route
	 *
	 * @return Router
	 */
	public function where(){
		$args = func_get_args();
		if (count($args) == 1 && is_array($args[0])){
			$regex = $args[0];
		}elseif (count($args) == 2 && is_string($args[0]) && is_string($args[1])){
			$regex = [$args[0] => $args[1]];
		}else{
			err('Invalid arguments in the route regex definition.');
		}
		if ($this->lastRouteIsGlobal){
			foreach(self::getRequestAccepted() as $acceptedMethod){
				$this->routeRegexs[$acceptedMethod][$this->routes[$acceptedMethod][count($this->routes[$acceptedMethod]) - 1]] = $regex;
			}
		}else{
			$this->routeRegexs[$this->lastRouteMethod][$this->routes[$this->lastRouteMethod][count($this->routes[$this->lastRouteMethod]) - 1]] = $regex;
		}
		return $this;
	}

	/**
	 * Define the name of a newly created route
	 *
	 * @param string $routeName
	 * @return Router
	 */
	public function setName($routeName){
		$this->routeNames[$routeName] = $this->lastRouteMethod.':'.(count($this->routes[$this->lastRouteMethod]) - 1);
		return $this;
	}

	/**
	 * Alias from setName function 
	 *
	 * @param string $routeName
	 * @return void
	 */
	public function name($routeName){
		return $this->setName($routeName);
	}

	/**
	 * Finds a route by name
	 *
	 * @param string $routeName
	 * @param array $params
	 * @return string|boolean
	 */
	public function getRoute($routeName, $params = []){
		if (isset($this->routeNames[$routeName])){
			list($method, $index) = explode(':', $this->routeNames[$routeName]);
			$route = self::filterRoute($this->routes[$method][$index]);
			foreach ($params as $key => $value){
				$route = str_replace(':'.$key, $value, $route);
			}
			return $route;
		}
		return false;
	}

	/**
	 * Returns the current method or GET by default
	 *
	 * @return string
	 */
	public function getMethod(){
		return isset($_POST['_method']) && in_array($_POST['_method'], self::getRequestAccepted()) ? $_POST['_method'] : (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');
	}

	/**
	 * Returns the content-type of the current request
	 *
	 * @return string|null
	 */
	public function getContentType(){
		return isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
	}

	/**
	 * Return an array with the names and values of the route variables
	 *
	 * @param string $route
	 * @return array
	 */
	public function getParamNames($route){
		preg_match_all('@:([\w]+)@', $route, $paramNames, PREG_PATTERN_ORDER);
		return $paramNames[0];
	}

	/**
	 * Convert a route to acceptable regex
	 *
	 * @param mixed $matches
	 * @return string
	 */
	public function convertToRegex($matches){
		if (!isset($this->routeRegexs[self::getMethod()][$this->lastRoute][substr($matches[0], 1)])) return '([\w_\-\.]+)';
		return '('.$this->routeRegexs[self::getMethod()][$this->lastRoute][substr($matches[0], 1)].')';
	}

	/**
	 * Returns the route without the variables
	 *
	 * @param string $pattern
	 * @param boolean $lastBar
	 * @return string
	 */
	public function getNonVariables($pattern){
		$return = [];
		foreach(explode('/', $pattern) as $i => $v){
			if(!preg_match('/^[\:]/i', $v)){
				$return[$i] = $v;
			}
		}
		return implode('/', $return);
	}

	/**
	 * Removes characters dedicated to route authentication control
	 *
	 * @param string $route
	 * @return string
	 */
	private function filterRoute($route){
		return trim(str_replace(['@', '#', '+'], '', $route), '/');
	}

	/**
	 * Search for the route that most closely matches the current URI
	 *
	 * @return void
	 */
	public function searchRoute(){
		foreach ($this->routes[self::getMethod()] as $route){
			if (is_array($route)){
				$file = current($route);
				$route = key($route);
			}else{
				$file = $route;
			}
			$this->lastRoute = $route;
			$routeFiltred = self::filterRoute($route);
			$fileFiltred = self::filterRoute($file);

			$patternAsRegex = preg_replace_callback('@:[\w]+@', [$this, 'convertToRegex'], $routeFiltred);

			if (preg_match('@^'.$patternAsRegex.($file != $route ? '$':'').'@', $this->uri, $paramValues)){
				// apply authentication control
				foreach (['@', '#', '+'] as $i){
					if (strpos($file, $i) > -1){
						if($i == '@' && !is_logged()) err(0);
						elseif($i == '#' && !is_logged()) return self::include(isset(config()->routes->auth_login) ? config()->routes->auth_login : 'login');
						elseif($i == '+' && is_logged()) r(isset(config()->routes->auth_not_logged) ? config()->routes->auth_not_logged : '/');
					}
				}
				// converts everything after the route into sequential variables
				$params = $paramValues[0] ? explode("/", trim(explode($paramValues[0], $this->uri)[1], '/')) : [];
				if (count($params))
					$_GET = array_merge($_GET, $params);

				if (!in_array(self::getMethod(), ['GET', 'POST']) && self::getContentType() == 'application/x-www-form-urlencoded'){
					$input_contents = file_get_contents('php://input');
					if (function_exists('mb_parse_str')){
						mb_parse_str($input_contents, $post_vars);
					}else{
						parse_str($input_contents, $post_vars);
					}
					$_POST = array_merge($_POST, $post_vars);
				}

				// set the parameters the route contains ex cat/:id => id = 123
				if (count($paramValues) > 1){
					$fileFiltred = self::getNonVariables($fileFiltred);
					array_shift($paramValues);
					foreach (self::getParamNames(self::filterRoute($file)) as $index => $value){
						$this->params[substr($value, 1)] = urldecode($paramValues[$index]);
					}
				}

				$callable = isset($this->callables[self::getMethod()][$file]) ? $this->callables[self::getMethod()][$file] : $fileFiltred;

				if (is_string($callable) && preg_match('/^[a-zA-Z\d\\\\]+[\:][\w\d]+$/', $callable)) {
					$exp = explode(':', $callable);

					$obj = filter_var($exp[0], FILTER_SANITIZE_STRING);
					$obj = new $obj();

					$callable = [$obj, filter_var($exp[1], FILTER_SANITIZE_STRING)];
				}

				if(is_array($callable) || is_callable($callable))
					call_user_func_array($callable, array_values($this->params));
				else
					self::include($callable);
				return;
			}
		}
		self::include($this->uri);
	}

	/**
	 * Render the matched view
	 *
	 * @param string $file
	 * @return void
	 */
	private function include($file){
		new Render($file, array_merge($this->params, globals()));
	}
}