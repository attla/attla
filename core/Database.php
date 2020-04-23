<?php

namespace Attla;

class Database
{
	/**
	 * Stores the PDO instance
	 *
	 * @var PDO
	 */
	private $con = null;

	/**
	 * Constructor
	 *
	 * Start connection
	 */
	public function __construct(){
		self::connect();
	}

	/**
	 * Checks whether a database instance exists, otherwise start the connection
	 *
	 * @return void
	 */
	private function connect(){
		if ($this->con != null){
			return true;
		}else{
			try{
				$this->con = new \PDO(config()->db->driver.':host='.config()->db->host.';charset=utf8;dbname='.config()->db->database.';port='.config()->db->port, config()->db->username, config()->db->password);
				return true;
			}catch (PDOException $e){
				err('Error unable to connect to the database: <b>'.$e->getMessage().'</b>');
				return false;
			}
		}
	}

	/**
	 * Executes a query, if get only 1 result, it will return it
	 *
	 * @param string $query
	 * @param array $bindParams
	 * @return array|boolean
	 */
	private function execute($query, $bindParams = []){
		if (self::connect()){
			$instance = $this->con->prepare($query);
			if(is_array($bindParams)) foreach($bindParams as $k => $v) $instance->bindParam($k, $v);
			$instance->execute();
			return count($r = $instance->fetchAll(\PDO::FETCH_ASSOC)) == 1 ? $r[0] : $r;
		}
		return false;
	}

	/**
	 * Executes a query
	 *
	 * @param string $query
	 * @param array $bindParams
	 * @return array|boolean
	 */
	public function query($query = '', $bindParams = []){
		return !$bindParams && isset(config()->cache) && config()->cache ? $this->cache($query) : $this->execute($query, $bindParams);
	}

	/**
	 * Search on a table
	 *
	 * @param string $table
	 * @param array $keys
	 * @return array|boolean
	 */
	public function find($table, $keys = []){
		if(!$keys || !is_array($keys)) return false;
		$query = '';
		foreach($keys as $k => $v) $query .= "$k = '{$v}'".($v != end($keys) ? ' AND ' : '');
		return self::execute("SELECT * FROM $table WHERE $query");
	}

	/**
	 * Create and read cached database entries
	 *
	 * @param string $query
	 * @return array|boolean
	 */
	private function cache($query = ''){
		$file = VPATH.'cache/'.sha1($query);
		$exp = isset(config()->cache_time) && is_int(config()->cache_time) ? config()->cache_time : 7200;
		return is_file($file) ? (time() - filemtime($file) > $exp ? $this->write($this->execute($query), $file) : $this->read($file)) : $this->write($this->execute($query), $file);
	}

	/**
	 * Read cached database entries
	 *
	 * @param string $file
	 * @return array|boolean
	 */
	private function read($file){
		if(!is_file($file)) return false;
		return maybe_unserialize(file_get_contents($file));
	}

	/**
	 * Write cache database entries
	 *
	 * @param mixed $data
	 * @param string $file
	 * @return array|boolean
	 */
	private function write($data, $file){
		if(is_file($file)) @unlink($file);
		if(!$f = @fopen($file, 'w+b')) return false;
		if (flock($f, LOCK_EX)){
			if(fwrite($f, maybe_serialize($data)) === false) return false;
			flock($f, LOCK_UN);
		}else{
			return false;
		}
		fclose($f);
		return $data;
	}
}