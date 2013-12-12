<?php
# MySQL Class PHPs
# @package 
# @since 2.5
# @version 0.3
# @link http://github.com/meownosaurus/mysql-class-php

Class connectDB {
	
	# Base variables for credentials to MySQL database
	# The variables have been declared as private. This
	# means that they will only be available with the 
	# Database class
	private $db_host = ""; // Change as required
	private $db_user = ""; // Change as required
	private $db_pass = ""; // Change as required
	private $db_name = ""; // Change as required
	private $persistent = false;
	
	# Extra variables that are required by other function such as boolean con variable
	var $link = null;
	var $result= false;
	var $error = null;

 
	/*Constructor function this will run when we call the class */
	function connectDB ($db_host='localhost', $db_user, $db_pass, $db_name, $persistent=false) {
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_name = $db_name;
		$this->persistent = $persistent;
	}

 	# ===================================
	# Private Functions
	# ===================================

	// Connects class to database
	private function connect() {
		/* Connect to the MySQl Server */
		if ($this->persistent) {
			$this->link = mysql_pconnect($this->db_host, $this->db_user, $this->db_pass);
		} else {
			$this->link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		}
		if (!$this->link) {
			$this->error = 'Could not connect to server: ' . mysql_error($this->link);
			return false;
		}

		/* Select the requested DB */
		if (@!mysql_select_db($this->db_name, $this->link)) {
			$this->error = 'Could not connect to database: ' . mysql_error($this->link);
			return false;
		}
		mysql_query("SET character_set_results='utf8'");
		mysql_query("SET character_set_client='utf8'");
		mysql_query("SET character_set_connection='utf8'");
		return true;
	}
 
	/* Close the connection */
	private function disconnect() {
		if($this->link){
			return (@mysql_close($this->link));
		}
	}

	/* Report error if error_reporting set to true */
	private function error() {
		self::connect();
		if ($this->error_reporting) {
			return (mysql_error()) ;
		}
		self::disconnect();
	}

 	# ===================================
	# Public Functions
	# ===================================

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
		return(@mysql_insert_id());
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