MySQL Class PHP (v.1.0)
------------

Usage
-----

```php
<?php
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
?>
```
