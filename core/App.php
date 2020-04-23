<?php

namespace Attla;

set_error_handler('err');

class App
{
	/**
	 * Constructor
	 *
	 * Define the constants, load the settings and instantiate the router class
	 */
	public function __construct(){
		$this->setConstants();
		$this->loadConfig();
		$this->setTimezone();

		if(isset(config()->debug) && config()->debug){
			ini_set("display_errors", true);
			error_reporting(E_ALL ^ E_NOTICE);
		}else{
			ini_set("display_errors", false);
			error_reporting(0);
		}

		$protocol = isset($_SERVER['ORIG_PATH_INFO']) ? 'ORIG_PATH_INFO' : 'PATH_INFO';
		$index = isset(config()->routes->index) ? config()->routes->index : 'index';
		$excludeRoutes = isset(config()->routes->exclude_routes) ? config()->routes->exclude_routes : [];
		$this->router = new Router($protocol, $index, $excludeRoutes);
	}

	/**
	 * Define the constants
	 *
	 * @return void
	 */
	private function setConstants(){
		!defined('DS') && define('DS', DIRECTORY_SEPARATOR);
		!defined('ROOT') && define('ROOT', realpath(getcwd()).DS);

		!defined('VPATH') && define('VPATH', ROOT.'public'.DS);		// view path

		!defined('URL') && define('URL', 'http'.(isset($_SERVER['HTTPS'])?'s':'').'://'.rtrim($_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']), '/\\').'/');
	}

	/**
	 * Load the configuration
	 *
	 * @return void
	 */
	private function loadConfig(){
		$cFile = ROOT.'config.json';
		if(!is_file($cFile))
			err('Configuration file not found.');

		config(json_decode(preg_replace('/\/\*.*\*\//Us', '', preg_replace('![ \t]*//.*[ \t]*[\r\n]!', '', file_get_contents($cFile)))));
		if(!config()){
			err('Error to parse configuration file.');
		}
	}

	/**
	 * Set the timezone and locale
	 *
	 * @return void
	 */
	private function setTimezone(){
		if (isset(config()->default_timezone)) date_default_timezone_set(config()->default_timezone);
		if (!isset(config()->locale)) return;
		switch(count(config()->locale)){
			case 4:
				setlocale(LC_TIME, config()->locale[0], config()->locale[1], config()->locale[2], config()->locale[3]);
				break;
			case 3:
				setlocale(LC_TIME, config()->locale[0], config()->locale[1], config()->locale[2]);
				break;
			case 2:
				setlocale(LC_TIME, config()->locale[0], config()->locale[1]);
				break;
			case 1:
				setlocale(LC_TIME, config()->locale[0]);
				break;
		}
	}

	/**
	 * Filters global variables and looks for the route that most closely matches the current URI
	 *
	 * @return void
	 */
	public function run(){
		if (isset(config()->filter_inputs) && config()->filter_inputs){
			$_GET = Security::filterInput($_GET);
			$_POST = Security::filterInput($_POST);
			$_REQUEST = Security::filterInput($_REQUEST);
			$_COOKIE = Security::filterInput($_COOKIE);
		}

		$this->router->searchRoute();
	}

	/**
	 * Captures get, post, put, delete methods and defines a route
	 *
	 * @return Router
	 */
	public function __call($method, $args){
		if (count($args) != 2)
			err('Invalid route args.');

		if (in_array(strtoupper($method), array_merge($this->router->getRequestAccepted(), $this->router->globalKeys))){
			$this->router->setRoute($method, $args[0], $args[1]);
			return $this->router;
		}elseif ($method == 'group' && count($args) == 2){
			$this->router->group($args[0], $args[1]);
		}else{
			err('Invalid route method.');
		}
	}
}