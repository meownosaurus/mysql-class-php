<?php
Class connectDB {

	private $host = '';
	private $user = '';
	private $password = '';
	private $database = '';
	private $persistent = false;
	private $error_reporting = false;
	var $link = null;
	var $result= false;

 
	/*constructor function this will run when we call the class */
	function connectDB ($host, $user, $password, $database, $error_reporting=true, $persistent=false) {
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->persistent = $persistent;
		$this->error_reporting = $error_reporting;
	}
 
	private function connect() {
		$charset = "SET NAMES UTF8";
		if ($this->persistent) {
			$func = 'mysql_pconnect';
		} else {
			$func = 'mysql_connect'; 
		}
		/* Connect to the MySQl Server */
		$this->link = $func($this->host, $this->user, $this->password);
		if (!$this->link) {
			return false;
		}
		/* Select the requested DB */
		if (@!mysql_select_db($this->database, $this->link)) {
			return false;
		}
		mysql_query($charset) or die('Invalid query: ' . mysql_error());
		return true;
	}
 
	/*close the connection */
	private function disconnect() {
		return (@mysql_close($this->link));
	}
 
	/* report error if error_reporting set to true */
	public function error() {
		self::connect();
		if ($this->error_reporting) {
			return (mysql_error()) ;
		}
		self::disconnect();
	}

	public function query($sql) {
		self::connect();
		$this->result = @mysql_query($sql, $this->link);
		return($this->result != false);
		self::disconnect();
	}
	
	public function affected_rows() {
		self::connect();
		return(@mysql_affected_rows($this->link));
		self::disconnect();
	}
 
	public function num_rows() {
		self::connect();
		return(@mysql_num_rows($this->result));
		self::disconnect();
	}
	
	public function fetch_object() {
		self::connect();
		return(@mysql_fetch_object($this->result, MYSQL_ASSOC));
		self::disconnect();
	}

	public function fetch_array() {
		self::connect();
		return(@mysql_fetch_array($this->result));
		self::disconnect();
	}
 
	public function fetch_assoc() {
		self::connect();
		return(@mysql_fetch_assoc($this->result));
		self::disconnect();
	}
 
	public function free_result() {
		self::connect();		
		return(@mysql_free_result($this->result));
		self::disconnect();
	}

	public function insert_id() {
		self::connect();
		return(@mysql_insert_id($this->link));
		self::disconnect();
	}

	public function get_query($query,$key='') {
		self::connect();
		$this->query($query);
		if($return = $this->fetch_array()){
			if($key)
				$return = $return[$key];
			return $return;
		}
		return 0;
		self::disconnect();
	}

	public function escape($string) {
		self::connect();
		if (function_exists('mysql_real_escape_string') && $this->link) {
			$string = mysql_real_escape_string($string, $this->link);
		} else {
			$string = mysql_escape_string($string);
		}
		return $string;
		self::disconnect();
   }

}
?>