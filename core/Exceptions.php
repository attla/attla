<?php

namespace Attla;

class Exceptions extends \Exception
{
	/**
	 * List of PHP errors
	 *
	 * @var array
	 */
	public $e_codes_list = [
		E_ERROR => 'Error',
		E_WARNING => 'Warning',
		E_PARSE => 'Parsing Error',
		E_NOTICE => 'Notice',
		E_CORE_ERROR => 'Core Error',
		E_CORE_WARNING => 'Core Warning',
		E_COMPILE_ERROR => 'Compile Error',
		E_COMPILE_WARNING => 'Compile Warning',
		E_USER_ERROR => 'User Error',
		E_USER_WARNING => 'User Warning',
		E_USER_NOTICE => 'User Notice',
		E_STRICT => 'Runtime Notice'
	];

	/**
	 * Prints a 404 error page
	 *
	 * @return void
	 */
	public function show_404(){
		self::show_error('P&#225;gina n&#227;o encontrada', 'A p&#225;gina que voc&#234; est&#225; procurando pode ter sido removida, teve seu<br>nome alterado ou est&#225; temporariamente indispon&#237;vel. Por favor, verifique<br>se o endereço da p&#225gina está escrito corretamente.', 404);
	}

	/**
	 * Prints a 401 error page
	 *
	 * @return void
	 */
	public function show_restrict_page(){
		return self::show_error('P&#225;gina restrita', 'Fa&#231;a <a href="'.route('login').'">Login</a> ou <a href="'.route('cadastro').'">crie uma conta</a> para ter acesso a esta p&#225;gina.', 401);
	}

	/**
	 * Show a error page
	 *
	 * @param string $title
	 * @param string $msg
	 * @param integer $code
	 * @return void
	 */
	public function show_error($title, $msg, $code = 500){
		set_status_header($code);
		if (is_file(VPATH.'error.php') || is_file(VPATH.'error.blade.php')){
			new Render('error', array_merge([
				'title' => $title,
				'message' => $msg,
				'code' => $code
			], globals()));
		}else{
			if($code == 500){
				$trace = $this->getTrace()[0];
				$backtrace = str_replace('\\', '/', $trace['file'])." [$trace[line]]";
			}
			
			die('<!DOCTYPE html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/><meta http-equiv="X-UA-Compatible" content="IE=edge"/><link rel="shortcut icon" type="image/png" href="'.assets('img/favico.png').'"/><title>'.($code!=200?$code.' - ':'').$title.'</title><style>html{-webkit-text-size-adjust:none}body{margin:0;padding:0;font-family:Malgun Gothic;background-color:#f5f5f5}#p{width:600px;margin:0 auto;padding:298px 0 0;text-align:center;position:absolute;transform:translateY(-25%);left:28%}#p p{margin:0 0 31px;padding:292px 0 0;font-size:14px;line-height:22px;color:#9ba1ad}#p p strong{display:block;margin-bottom:20px;font-size:26px;line-height:30px;color:#2a84d8}#p p b{display:block;font-size:15px}#p.go_home{min-width:66px;display:inline-block;padding:0 34px;font-size:14px;line-height:42px;color:#2a84d8;text-decoration:none;border:2px solid #2a84d8}.error_200{background:url("'.assets('img/http_200.png').'") no-repeat 50% 0;background-size:460px}.error_401{background:url("'.assets('img/http_401.png').'") no-repeat 50% 0;background-size:460px}.error_404{background:url("'.assets('img/http_404.png').'") no-repeat 50% 0;background-size:318px 260px}.error_418,.error_500{background:url("'.assets('img/http_500.png').'") no-repeat 50% 0;background-size:460px}</style></head><body><div id="p"><p class="error_'.$code.'"><strong>'.($code!=200?$code.' - ':'').$title.'</strong><b>'.$msg.'</b>'.($code==500?$backtrace:'').'</p></div></body></html>');
		}
	}

	/**
	 * Prints a PHP error
	 *
	 * @param integer $code
	 * @param string $msg
	 * @param string $file
	 * @param integer $line
	 * @return void
	 */
	public function show_php_error($code, $msg, $file, $line){
		$code = isset($this->e_codes_list[$code]) ? $this->e_codes_list[$code] : $code;
		print '<div style="font-family:sans-serif;font-size:small;margin:1em;position:relative;z-index:2147483647"><h1 style="margin:0;padding:.3em;font-size:1.4em;font-weight:bold;color:#ffffff;background-color:#ff0000">'.($code ? $code.' - ':'').$msg.'<button type="button" style="-webkit-appearance:none;padding:0;cursor:pointer;background:0 0;border:0;margin-top:1px;float:right;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2;font-size:17px" class="close" title="Esconder" onclick="this.parentNode.parentNode.remove()">x</button></h1><p style="margin:0;padding:.5em;border:.1em solid red;color:#000;background-color:#ffeeee"><b>File: '.$file.' [ Line: '.$line.' ]</b></p></div>';
	}
}