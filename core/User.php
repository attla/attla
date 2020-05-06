<?php

namespace Attla;

class User extends Encrypt
{
	/**
	 * Stores user data
	 *
	 * @var object
	 */
	private static $data = null;

	/**
	 * User database
	 *
	 * @var string
	 */
	private static $db = 'users';

	/**
	 * User authentication cookie
	 *
	 * @var string
	 */
	private static $cookie_name = '';

	/**
	 * User authentication cookie expiration
	 *
	 * @var integer
	 */
	private static $cookie_exp = 31556926;

	/**
	 * Stores the table field that best matches the search value
	 *
	 * @var string
	 */
	private static $type = '';

	/**
	 * Constructor
	 *
	 * Defines the name of the cookie and checks if the user is logged in
	 */
	public function __construct(){
		self::$cookie_name = config()->prefix.'sign';
		if(!self::is_logged()) self::check();
	}

	/**
	 * Get cookie name
	 *
	 * @return string
	 */
	private static function cookie_name(){
		return self::$cookie_name ? self::$cookie_name : self::$cookie_name = (isset(config()->prefix) ? config()->prefix : '').'sign';
	}

	/**
	 * Checks if the user is logged in
	 *
	 * @return boolean
	 */
	public static function is_logged(){
		return self::$data != null ? true : false;
	}

	/**
	 * Returns an index of user data
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function user($key = ''){
		return !$key ? self::$data : (isset(self::$data->$key) ? self::$data->$key : false);
	}

	/**
	 * Renew user session
	 *
	 * @return object
	 */
	public static function new_sign(){
		return self::sign(i('Attla\Database')->find(self::$db, ['id' => self::$data->id]));
	}

	/**
	 * Validates the authentication cookie
	 *
	 * @return object
	 */
	public static function check(){
		if (self::$data != null){
			return self::$data;
		}else{
			$cookie = isset($_COOKIE[self::cookie_name()]) ? $_COOKIE[self::cookie_name()] : false;
			self::$data = parent::is_jwt($cookie);
			if($cookie && !self::$data) self::logout(false);
			return self::$data;
		}
	}

	/**
	 * Obtains user data by key and value
	 *
	 * @param string $key
	 * @param string $value
	 * @return object|string
	 */
	private static function get_data_by($key = '', $value = ''){
		if (!$key || !$value) return self::error(0);
		$key = strtolower($key);
		if (!$value = trim($value," \t\n\r\0\x0B\xc2\xa0/")) return self::error(0);
		if (!self::$type){
			switch ($key){
				case 'id':
					if (!is_numeric($value) || ($value = intval($value)) < 1) return self::error(7);
					break;
				case 'user':case 'login':case 'usuario':case 'username':
					if (!is_username($value)) return self::error(5);
					break;
				case 'email':
					if (!is_email($value)) return self::error(6);
					break;
				default:
					return self::error(0);
			}
		}
		if ($data = i('Attla\Database')->find(self::$db, [$key => $value])){
			return $data;
		}else{
			return self::error(1, $key);
		}
	}

	/**
	 * Gets the field type of the table to search
	 *
	 * @return void
	 */
	private static function get_type($value = ''){
		if (is_numeric($value) && intval($value) > 0) $k = 'id';
		elseif (is_email($value)) $k = 'email';
		elseif (is_username($value)) $k = 'user';
		else $k = '';
		return self::$type = $k;
	}

	/**
	 * Gets an error message
	 *
	 * @param integer $erro
	 * @param string $type
	 * @return string
	 */
	private static function error($e, $type = 'login'){
		switch($e){
			case 0: return "Login inválido.";
			case 1: return "O $type não corresponde a nenhuma conta.";
			case 2: return 'Digite a sua senha corretamente!';
			case 3: return 'Captcha inválido.';
			case 4: return 'Token da sessão expirou, tente novamente!';
			case 5: return 'Usuário inválido. (Min. 3 caracteres)';#cadastro
			case 6: return 'E-mail inválido.';
			case 7: return 'ID inválido.';
			case 8: return 'Senhas inválidas. (Min. 6 caracteres)';
			case 9: return 'Senhas não conferem.';
			case 10: return "O $type já esta em uso.";
			case 11: return 'Informe os dados de login!';
			case 12: return 'Faça logout para prosseguir.';
			case 13: return 'Preencha os dados para prosseguir.';
			default: return 'Ocorreu um erro inesperado.';
		}
	}

	/**
	 * Check recaptcha response
	 *
	 * @param string $response
	 * @return boolean
	 */
	private static function check_captcha($response){
		$a = curl_init();
		curl_setopt($a, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?secret='.config()->recaptcha_secret_key.'&response='.$response.'&remoteip='.ip());
		curl_setopt($a, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($a, CURLOPT_USERAGENT, "Mozilla/6.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.7) Gencko/20050414 Firefox/1.0.3");
		curl_setopt($a, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($a, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($a, CURLOPT_RETURNTRANSFER, 1);
		$r = json_decode(curl_exec($a), true);
		return $r['success'] == true ? true : false;
	}

	/**
	 * Creates an authentication token
	 *
	 * @param array $data
	 * @param integer $exp
	 * @param string $redirect
	 * @return object
	 */
	public static function sign($data = [], $exp = 0, $redirect = ''){
		if (!$data) return;
		if (!$exp) $exp = time() + self::$cookie_exp;
		$jwt = parent::sign($data, $exp);
		setcookie(self::cookie_name(), $jwt, $exp, "/", $_SERVER['HTTP_HOST'], false, true);
		self::$data = (object) $data;
		if ($redirect) r($redirect);
		return self::$data;
	}

	/**
	 * Login
	 *
	 * @param array $data
	 * @return string|void
	 */
	public static function login($data = 0){
		if (!self::is_logged() && is_array($data)){
			if (CSRF::verify() && isset($data['user'], $data['pass'], $_POST[$data['user']], $_POST[$data['pass']])){
				$data['user'] = $_POST[$data['user']];
				$data['pass'] = $_POST[$data['pass']];
				$data['expire'] = time() + (isset($data['remember'], $_POST[$data['remember']]) && $_POST[$data['remember']] ? self::$cookie_exp : 3600);
				$data['captcha'] = isset($data['captcha'], $_POST[$data['captcha']]) && $_POST[$data['captcha']] ? self::check_captcha($_POST[$data['captcha']]) : true;
				$data['redirect'] = isset($data['redirect']) ? $data['redirect'] : '';
				extract($data);

				if($user&&$pass){
					if ($captcha && ($tipo = self::get_type($user)) && $data = self::get_data_by($tipo, $user)){
						if (is_array($data) && count($data) > 1){
							if (parent::hash_equals($pass, $data['pass'])){
								self::sign($data, $expire, $redirect);
							}else{
								return self::error(2);
							}
						}else{
							return $data;
						}
					}else{
						return self::error(3);
					}
				}else{
					return self::error(13);
				}
			}else{
				return empty($_POST) ? '' : self::error(4);
			}
		}else{
			return self::error(12);
		}
	}

	/**
	 * Register user
	 *
	 * @param array $data
	 * @return string|void
	 */
	public static function register($data = 0){
		if (!self::is_logged() && is_array($data)){
			if (CSRF::verify() && isset($data['fields'])){
				if(is_array($data['fields'])){
					$return = $label = '';
					$fields=[];
					foreach ($data['fields'] as $k => $v){
						if ($k=='captcha'||$k=='auto_login'||$k=='redirect') continue;
						$label=$k;
						if (isset($_POST[$k])){
							if ((!isset($v['required']) || $v['required']) && trim($_POST[$k]) === ''){
								$return = $v['empty'];
								break;
							}
							if (isset($v['check'])){
								foreach ($v['check'] as $v2){
									if (!isset($v2['function'], $v2['msg'])) continue;
									if (!$v2['function']($_POST[$k])){
										$return = $v2['msg'];
										break;
									}
								}
							}
							if (isset($v['exist']) && $v['exist'] && exist(self::$db,$k,$_POST[$k])) $return = 10;
							if ($k=='pass2' && !self::check_pwd($_POST['pass'],$_POST[$k])) $return = 9;
							if (!empty($return)) break;
							if ($k!='pass2') $fields[isset($v['alias']) ? $v['alias'] : $k] = isset($v['encrypt']) && $v['encrypt'] ? parent::hash($_POST[$k]) : $_POST[$k];
						}
					}
					if(!empty($return)) return is_numeric($return) ? self::error($return, $label) : $return;
				}else{
					return self::error(-1);
				}
				$data['captcha'] = isset($data['captcha'], $_POST[$data['captcha']]) && $_POST[$data['captcha']] ? self::check_captcha($_POST[$data['captcha']]) : true;
				$data['auto_login'] = isset($data['auto_login']) ? $data['auto_login'] : '';
				$data['redirect'] = isset($data['redirect']) ? $data['redirect'] : '';
				$data['extra'] = isset($data['extra']) ? $data['extra'] : [];
				unset($data['fields']);
				extract($data);
				$fields = $fields + $extra;

				query('INSERT INTO '.self::$db.' ('.implode(',',array_keys($fields)).") VALUES ('".implode("','",array_values($fields))."')");

				if ($captcha){
					if ($auto_login){
						$data = i('Attla\Database')->find(self::$db, ['user'=>$_POST['user']]);
						if (is_array($data) && count($data)>1){
							self::sign($data, 0, $redirect);
						}else{
							return $data;
						}
					}else{
						return true;
					}
				}else{
					return self::error(3);
				}
			}else{
				return empty($_POST) ? '' : self::error(4);
			}
		}else{
			return self::error(12);
		}
	}

	/**
	 * Checks if passwords are the same
	 *
	 * @return string $x
	 * @return string $y
	 * @return boolean
	 */
	private static function check_pwd($x, $y){
		return !$x || !$y || strcmp($x, $y) != 0 ? false : true;
	}

	/**
	 * Password recovery
	 *
	 * @param array $data
	 * @return string|void
	 */
	static function password($data = 0){
		if (is_array($data)){
			if (CSRF::verify() && isset($data['email'], $_POST[$data['email']])){
				$data['email'] = $_POST[$data['email']];
				$data['expire'] = time() + (isset($data['expire']) && is_numeric($data['expire']) ? $data['expire'] : 3600);
				$data['captcha'] = isset($data['captcha'], $_POST[$data['captcha']]) && $_POST[$data['captcha']] ? self::check_captcha($_POST[$data['captcha']]) : true;
				extract($data);

				if ($captcha){
					$data = i('Attla\Database')->find(self::$db, ['email' => $email]);
					if (is_array($data) && count($data) > 1){
						//envia o email com o link de recuperação
						$url = URL.'senha/'.$data['pass'];

						$mail = new PHPMailer;
						/*$mail->isSMTP();
						$mail->Host = '';
						$mail->SMTPAuth = true;
						$mail->Username = '';
						$mail->Password = '';
						$mail->SMTPSecure = 'ssl';
						$mail->Port = 465;*/
						$mail->CharSet = "utf8";

						$mail->FromName = config()->name;
						$mail->From = config()->email;
						$mail->AddAddress($email);

						$mail->isHTML(true);
						$mail->Subject = 'Recuperar senha ~ '.config()->name;
						$mail->Body = 'Para trocar a sua senha <a href="'.$url.'">Click aqui</a>.';

						if(!$mail->send()){
							return 'Erro ao enviar o email: '.$mail->ErrorInfo;;
						}else{
							$GLOBALS['msg_ok'] = true;
							return 'Um email foi enviado com mais instruções.';
						}
						
					}else{
						return self::error(1, 'E-mail');
					}
				}else{
					return self::error(3);
				}
			}else{
				if (get(0)){
					#die(var_dump(get(0)));
					if ($data = i('Attla\Database')->find(self::$db, ['pass' => get(0)])){
						if (post('pass') && post('pass2')){
							$pass = post('pass');
							$pass2 = post('pass2');
							if (!$pass){
								$a = "Digite uma nova senha.";
							}elseif (!$pass2){
								$a = "Confirme sua nova senha.";
							}
							if (empty($a)){
								if ($data['tipo'] < ADMIN){
									if (is_pwd($pass)){
										if (check_pwd($pass,$pass2)){
											$nova_senha = parent::hash($pass);
											query("UPDATE users SET pass = '$nova_senha' WHERE id = '$data[id]'");
											$a = "Senha atualizada com sucesso!";
											$GLOBALS['msg_ok'] = true;
											user::logout(42);
										}else{
											$a = "Novas senhas não conferem.";
										}
									}else{
										$a = "Nova senha inválida. (Min. 6 caracteres)";
									}
								}else{
									$a = 'Não é permitido trocar a senha dessa conta';
								}
							}
						}else{
							$a = empty($_POST) ? '' : self::error(4);
						}
						return [$a,true];
					}else{
						return ['Este link é invalido', false];
					}
					
				}else{
					return empty($_POST) ? '' : self::error(4);
				}
			}
		}
	}

	/**
	 * Checks if a user exists by email and redirects to the correct page
	 *
	 * @return string|void
	 */
	public static function identification($data){
		if (is_array($data)){
			if (CSRF::verify() && isset($data['email'], $_POST[$data['email']])){
				$data['email'] = $_POST['email'];
				$data['exist'] = isset($data['exist']) ? $data['exist'] : '';
				$data['not_exist'] = isset($data['not_exist']) ? $data['not_exist'] : '';
				$data['captcha'] = isset($data['captcha']) && $data['captcha'] ? self::check_captcha($data['captcha']) : true;
				extract($data);

				if($captcha){
					$data = i('Attla\Database')->find(self::$db, ['email' => $email]);
					if (is_array($data) && count($data) > 1){
						r(str_replace('%email', $email, $exist));
					}else{
						r(str_replace('%email', $email, $not_exist));
					}
				}else{
					return self::error(3);
				}
				
			}else{
				return empty($_POST) ? '' : self::error(4);
			}
		}
	}

	/**
	 * Logout
	 *
	 * @return void
	 */
	public static function logout($location = ''){
		if(isset($_COOKIE[self::cookie_name()])){
			setcookie(self::cookie_name(), '', -1, "/", $_SERVER['HTTP_HOST'], false, true);
			$_COOKIE[self::cookie_name()] = null;
			self::$data=null;
		}
		if($location !== false) r($location ? $location : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));
	}
}
