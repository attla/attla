<?php

namespace Attla;

class UserAgent
{
	/**
	 * Current user-agent
	 *
	 * @var string
	 */
	protected $agent = null;

	/**
	 * Flag for if the user-agent belongs to a browser
	 *
	 * @var boolean
	 */
	protected $isBrowser = false;

	/**
	 * Flag for if the user-agent is a robot
	 *
	 * @var boolean
	 */
	protected $isRobot = false;

	/**
	 * Flag for if the user-agent is a mobile browser
	 *
	 * @var boolean
	 */
	protected $isMobile = false;

	/**
	 * Current user-agent platform
	 *
	 * @var string
	 */
	protected $platform = 'Unknown Platform';

	/**
	 * Current user-agent browser
	 *
	 * @var string
	 */
	protected $browser = '';

	/**
	 * Current user-agent version
	 *
	 * @var string
	 */
	protected $version = '';

	/**
	 * Current user-agent mobile name
	 *
	 * @var string
	 */
	protected $mobile = '';

	/**
	 * Current user-agent robot name
	 *
	 * @var string
	 */
	protected $robot = '';

	/**
	 * Current language browser
	 *
	 * @var array
	 */
	protected $language = [];

	/**
	 * Current charset browser
	 *
	 * @var array
	 */
	protected $charset = [];

	protected $platforms = [
		'windows nt 10.0'	=> 'Windows 10',
		'windows nt 6.3'	=> 'Windows 8.1',
		'windows nt 6.2'	=> 'Windows 8',
		'windows nt 6.1'	=> 'Windows 7',
		'windows nt 6.0'	=> 'Windows Vista',
		'windows nt 5.2'	=> 'Windows 2003',
		'windows nt 5.1'	=> 'Windows XP',
		'windows nt 5.0'	=> 'Windows 2000',
		'windows nt 4.0'	=> 'Windows NT 4.0',
		'winnt4.0'			=> 'Windows NT 4.0',
		'winnt 4.0'			=> 'Windows NT',
		'winnt'				=> 'Windows NT',
		'windows 98'		=> 'Windows 98',
		'win98'				=> 'Windows 98',
		'windows 95'		=> 'Windows 95',
		'win95'				=> 'Windows 95',
		'windows phone'		=> 'Windows Phone',
		'windows'			=> 'Unknown Windows OS',
		'android'			=> 'Android',
		'blackberry'		=> 'BlackBerry',
		'iphone'			=> 'iOS',
		'ipad'				=> 'iOS',
		'ipod'				=> 'iOS',
		'os x'				=> 'Mac OS X',
		'ppc mac'			=> 'Power PC Mac',
		'freebsd'			=> 'FreeBSD',
		'ppc'				=> 'Macintosh',
		'linux'				=> 'Linux',
		'debian'			=> 'Debian',
		'sunos'				=> 'Sun Solaris',
		'beos'				=> 'BeOS',
		'apachebench'		=> 'ApacheBench',
		'aix'				=> 'AIX',
		'irix'				=> 'Irix',
		'osf'				=> 'DEC OSF',
		'hp-ux'				=> 'HP-UX',
		'netbsd'			=> 'NetBSD',
		'bsdi'				=> 'BSDi',
		'openbsd'			=> 'OpenBSD',
		'gnu'				=> 'GNU/Linux',
		'unix'				=> 'Unknown Unix OS',
		'symbian'			=> 'Symbian OS'
	];

	// The order of this array should NOT be changed. Many browsers return
	// multiple browser types so we want to identify the sub-type first.
	protected $browsers = [
		'OPR'				=> 'Opera',
		'Flock'				=> 'Flock',
		'Edge'				=> 'Spartan',
		'Chrome'			=> 'Chrome',
		// Opera 10+ always reports Opera/9.80 and appends Version/<real version> to the user agent string
		'Opera.*?Version'	=> 'Opera',
		'Opera'				=> 'Opera',
		'MSIE'				=> 'Internet Explorer',
		'Internet Explorer'	=> 'Internet Explorer',
		'Trident.* rv'		=> 'Internet Explorer',
		'Shiira'			=> 'Shiira',
		'Firefox'			=> 'Firefox',
		'Chimera'			=> 'Chimera',
		'Phoenix'			=> 'Phoenix',
		'Firebird'			=> 'Firebird',
		'Camino'			=> 'Camino',
		'Netscape'			=> 'Netscape',
		'OmniWeb'			=> 'OmniWeb',
		'Safari'			=> 'Safari',
		'Mozilla'			=> 'Mozilla',
		'Konqueror'			=> 'Konqueror',
		'icab'				=> 'iCab',
		'Lynx'				=> 'Lynx',
		'Links'				=> 'Links',
		'hotjava'			=> 'HotJava',
		'amaya'				=> 'Amaya',
		'IBrowse'			=> 'IBrowse',
		'Maxthon'			=> 'Maxthon',
		'Ubuntu'			=> 'Ubuntu Web Browser',
		'Vivaldi'			=> 'Vivaldi'
	];

	protected $mobiles = [
		// legacy array, old values commented out
		'mobileexplorer'		=> 'Mobile Explorer',
		// 'openwave'				=> 'Open Wave',
		// 'opera mini'				=> 'Opera Mini',
		// 'operamini'				=> 'Opera Mini',
		// 'elaine'					=> 'Palm',
		'palmsource'			=> 'Palm',
		// 'digital paths'			=> 'Palm',
		// 'avantgo'				=> 'Avantgo',
		// 'xiino'					=> 'Xiino',
		'palmscape'				=> 'Palmscape',
		// 'nokia'					=> 'Nokia',
		// 'ericsson'				=> 'Ericsson',
		// 'blackberry'				=> 'BlackBerry',
		// 'motorola'				=> 'Motorola'

		// Phones and Manufacturers
		'motorola'				=> 'Motorola',
		'nokia'					=> 'Nokia',
		'palm'					=> 'Palm',
		'iphone'				=> 'Apple iPhone',
		'ipad'					=> 'iPad',
		'ipod'					=> 'Apple iPod Touch',
		'sony'					=> 'Sony Ericsson',
		'ericsson'				=> 'Sony Ericsson',
		'blackberry'			=> 'BlackBerry',
		'cocoon'				=> 'O2 Cocoon',
		'blazer'				=> 'Treo',
		'lg'					=> 'LG',
		'amoi'					=> 'Amoi',
		'xda'					=> 'XDA',
		'mda'					=> 'MDA',
		'vario'					=> 'Vario',
		'htc'					=> 'HTC',
		'samsung'				=> 'Samsung',
		'sharp'					=> 'Sharp',
		'sie-'					=> 'Siemens',
		'alcatel'				=> 'Alcatel',
		'benq'					=> 'BenQ',
		'ipaq'					=> 'HP iPaq',
		'mot-'					=> 'Motorola',
		'playstation portable'	=> 'PlayStation Portable',
		'playstation 3'			=> 'PlayStation 3',
		'playstation vita'		=> 'PlayStation Vita',
		'hiptop'				=> 'Danger Hiptop',
		'nec-'					=> 'NEC',
		'panasonic'				=> 'Panasonic',
		'philips'				=> 'Philips',
		'sagem'					=> 'Sagem',
		'sanyo'					=> 'Sanyo',
		'spv'					=> 'SPV',
		'zte'					=> 'ZTE',
		'sendo'					=> 'Sendo',
		'nintendo dsi'			=> 'Nintendo DSi',
		'nintendo ds'			=> 'Nintendo DS',
		'nintendo 3ds'			=> 'Nintendo 3DS',
		'wii'					=> 'Nintendo Wii',
		'open web'				=> 'Open Web',
		'openweb'				=> 'OpenWeb',

		// Operating Systems
		'android'				=> 'Android',
		'symbian'				=> 'Symbian',
		'SymbianOS'				=> 'SymbianOS',
		'elaine'				=> 'Palm',
		'series60'				=> 'Symbian S60',
		'windows ce'			=> 'Windows CE',

		// Browsers
		'obigo'					=> 'Obigo',
		'netfront'				=> 'Netfront Browser',
		'openwave'				=> 'Openwave Browser',
		'mobilexplorer'			=> 'Mobile Explorer',
		'operamini'				=> 'Opera Mini',
		'opera mini'			=> 'Opera Mini',
		'opera mobi'			=> 'Opera Mobile',
		'fennec'				=> 'Firefox Mobile',

		// Other
		'digital paths'			=> 'Digital Paths',
		'avantgo'				=> 'AvantGo',
		'xiino'					=> 'Xiino',
		'novarra'				=> 'Novarra Transcoder',
		'vodafone'				=> 'Vodafone',
		'docomo'				=> 'NTT DoCoMo',
		'o2'					=> 'O2',

		// Fallback
		'mobile'				=> 'Generic Mobile',
		'wireless'				=> 'Generic Mobile',
		'j2me'					=> 'Generic Mobile',
		'midp'					=> 'Generic Mobile',
		'cldc'					=> 'Generic Mobile',
		'up.link'				=> 'Generic Mobile',
		'up.browser'			=> 'Generic Mobile',
		'smartphone'			=> 'Generic Mobile',
		'cellphone'				=> 'Generic Mobile'
	];

	// There are hundreds of bots but these are the most common.
	protected $robots = [
		'googlebot'				=> 'Googlebot',
		'msnbot'				=> 'MSNBot',
		'baiduspider'			=> 'Baiduspider',
		'bingbot'				=> 'Bing',
		'slurp'					=> 'Inktomi Slurp',
		'yahoo'					=> 'Yahoo',
		'ask jeeves'			=> 'Ask Jeeves',
		'fastcrawler'			=> 'FastCrawler',
		'infoseek'				=> 'InfoSeek Robot 1.0',
		'lycos'					=> 'Lycos',
		'yandex'				=> 'YandexBot',
		'mediapartners-google'	=> 'MediaPartners Google',
		'CRAZYWEBCRAWLER'		=> 'Crazy Webcrawler',
		'adsbot-google'			=> 'AdsBot Google',
		'feedfetcher-google'	=> 'Feedfetcher Google',
		'curious george'		=> 'Curious George',
		'ia_archiver'			=> 'Alexa Crawler',
		'MJ12bot'				=> 'Majestic-12',
		'Uptimebot'				=> 'Uptimebot'
	];

	/**
	 * Constructor
	 *
	 * Sets the User Agent and runs the compilation routine
	 */
	public function __construct(){
		if(isset($_SERVER['HTTP_USER_AGENT'])) $this->agent = trim($_SERVER['HTTP_USER_AGENT']);
		if(!is_null($this->agent)) self::compileData();
	}

	/**
	 * Compile the user-agent data
	 *
	 * @return void
	 */
	private function compileData(){
		foreach ($this->platforms as $k => $v){
			if (preg_match("|".preg_quote($k)."|i", $this->agent)){
				$this->platform = $v;
				break;
			}
		}
		foreach (['setRobot', 'setBrowser', 'setMobile'] as $function) if (self::$function() === true) break;
	}

	/**
	 * Set the robot
	 *
	 * @return boolean
	 */
	private function setRobot(){
		foreach ($this->robots as $k => $v){
			if (preg_match("|".preg_quote($k)."|i", $this->agent)){
				$this->isRobot = true;
				$this->robot = $v;
				self::setMobile();
				return true;
			}
		}
		return false;
	}

	/**
	 * Set the browser
	 *
	 * @return boolean
	 */
	private function setBrowser(){
		foreach ($this->browsers as $k => $v){
			if (preg_match("|".preg_quote($k).".*?([0-9\.]+)|i", $this->agent, $m)){
				$this->isBrowser = true;
				$this->version = $m[1];
				$this->browser = $v;
				self::setMobile();
				return true;
			}
		}
		return false;
	}

	/**
	 * Set the mobile device
	 *
	 * @return boolean
	 */
	private function setMobile(){
		foreach ($this->mobiles as $k=>$v){
			if (false !== (strpos(strtolower($this->agent),$k))){
				$this->isMobile = true;
				$this->mobile = $v;
				return true;
			}
		}
		return false;
	}

	/**
	 * Set the language
	 *
	 * @return void
	 */
	private function setLanguage(){
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE'] != ''){
			$l = preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));
			$this->language = explode(',',$l);
		}
		if(!$this->language) $this->language = ['Undefined'];
	}

	/**
	 * Set the charset
	 *
	 * @return void
	 */
	private function setCharset(){
		if (isset($_SERVER['HTTP_ACCEPT_CHARSET']) && $_SERVER['HTTP_ACCEPT_CHARSET'] != ''){
			$c = preg_replace('/(;q=.+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));
			$this->charset = explode(',',$c);
		}
		if(!$this->charset) $this->charset = ['Undefined'];
	}

	/**
	 * Is browser
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function is_browser($key = ''){
		return !$key ? $this->isBrowser : (isset($this->browsers[$key]) && $this->browser == $this->browsers[$key]);
	}

	/**
	 * Is robot
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function is_robot($key = ''){
		return !$key ? $this->isRobot : (isset($this->robots[$key]) && $this->robot == $this->robots[$key]);
	}

	/**
	 * Is mobile
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function is_mobile($key = ''){
		return !$key ? $this->isMobile : (isset($this->mobiles[$key]) && $this->mobile == $this->mobiles[$key]);
	}

	/**
	 * Is this a referral from another site?
	 *
	 * @return boolean
	 */
	public function is_referral(){
		return (!isset($_SERVER['HTTP_REFERER']) or $_SERVER['HTTP_REFERER'] == '') ? false : true;
	}

	/**
	 * Get platform
	 *
	 * @return string
	 */
	public function platform(){
		return $this->platform;
	}

	/**
	 * Get browser name
	 *
	 * @return string
	 */
	public function browser(){
		return $this->browser;
	}

	/**
	 * Get the browser version
	 *
	 * @return string
	 */
	public function version(){
		return $this->version;
	}

	/**
	 * Get the robot name
	 *
	 * @return string
	 */
	public function robot(){
		return $this->robot;
	}

	/**
	 * Get the mobile device
	 *
	 * @return string
	 */
	public function mobile(){
		return $this->mobile;
	}

	/**
	 * Get the IP
	 *
	 * @return string
	 */
	public function ip(){
		return isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Get the referrer
	 *
	 * @return string
	 */
	public function referrer(){
		return (!isset($_SERVER['HTTP_REFERER']) or $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
	}

	/**
	 * Search for a aceppted language
	 *
	 * @return boolean
	 */
	public function accept_lang($language = 'en'){
		return (in_array(strtolower($language), self::language(), true));
	}

	/**
	 * Search for a aceppted charset
	 *
	 * @return boolean
	 */
	public function accept_charset($charset = 'utf-8'){
		return (in_array(strtolower($charset), self::charset(), true));
	}

	/**
	 * Get the language
	 *
	 * @return array
	 */
	public function language(){
		if(!$this->language) self::setLanguage();
		return $this->language;
	}

	/**
	 * Get the charset
	 *
	 * @return array
	 */
	public function charset(){
		if(!$this->charset) $this->setCharset();
		return $this->charset;
	}
}