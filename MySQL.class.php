<?php
/*
//Simply include this file on your page
require_once("MySQL.class.php");

//Set up all yor paramaters for connection
$db = new connectDB("localhost","username","password","database",$error_reporting=false,$persistent=false);
  
//Query the database now the connection has been made
$db->query("SELECT * FROM table") or die($db->error());
 
//You have several options on ways of fetching the data
//as an example I shall use
while($row=$db->fetch_array()) {
//do some stuff
}
*/
Class connectDB {
	/* 
	 * Create variables for credentials to MySQL database
	 * The variables have been declared as private. This
	 * means that they will only be available with the 
	 * Database class
	 */
	private $db_host = "";  // Change as required
	private $db_user = "";  // Change as required
	private $db_pass = "";  // Change as required
	private $db_name = "";	// Change as required
	private $persistent = false;
	private $error_reporting = false;
	
	/*
	 * Extra variables that are required by other function such as boolean con variable
	 */
	var $link = null;
	var $result= false;

 
	/*Constructor function this will run when we call the class */
	function connectDB($db_host, $db_user, $db_pass, $db_name, $error_reporting=true, $persistent=false) {
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_name = $db_name;
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
		$this->link = $func($this->db_host, $this->db_user, $this->db_pass);
		if (!$this->link) {
			return false;
		}
		/* Select the requested DB */
		if (@!mysql_select_db($this->db_name, $this->link)) {
			return false;
		}
		@mysql_query("set character_set_results='utf8'");
		@mysql_query("set character_set_client='utf8'");
		@mysql_query("set character_set_connection='utf8'");
		return true;
	}
 
	/* Close the connection */
	private function disconnect() {
		return (@mysql_close($this->link));
	}
 
	/* Report error if error_reporting set to true */
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