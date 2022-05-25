<?php
/*
	Inventory system
	
	Controller Subsystem
	
	Contains lower level logic for the pages and other subsystems.	
*/

// Basic model for the Database Driver, present so the application can adapt to other database servers.
interface DatabaseDriver {
	// Connect ( <the location reference for this server>, <the username for the server>, <the password for the server> ) -> success?
	function Connect(String $location, String $username, String $password) : bool;
	// SelectFromTable ( <table name>, array ( <field1>, ... , <fieldn> ), ( <limit: eg "ID = 1">, ..., <limit>), <Starting result, like 0>, <Result count, like 10>) -> success?
	function SelectFromTable(String $table, Array $fields = array(), Array $limits = array(), Int $Start = -1, Int $NumResults = -1): ?Array;
	// InsertIntoTable ( <table name>, array ( <key1> => <value1>,
	//										   ...,
	//										   <keyn> => <valuen>) -> success?
	function InsertIntoTable(String $table, Array $fieldsvalues) : bool;
}

// Dummy, Default Driver.
class DefaultSQLDriver implements DatabaseDriver {
	function Connect(String $location, String $username, String $password) : bool 
	{
		// Is this real life?
		return false /* No. */;
	}
	
	function SelectFromTable(String $table, Array $fields = array(), Array $limits = array(), Int $Start = -1, Int $NumResults = -1): ?Array
	{
		// Or is this just fantasy?
		return false /* Yep. */;
	}
	
	function InsertIntoTable(String $table, Array $fieldsvalues) : bool
	{
		// ... I don't remember the next line off hand.
		return false; // At least I don't have to tell momma that I killed a man.
	}
}

// Driver for MySQL and MariaDB.
class MySQLDriver implements DatabaseDriver {
	var $innerMysqli;
	
	function __construct()
	{
		$this->innerMysqli = new mysqli();
	}
	
	function Connect(String $location, String $username, String $password) : bool 
	{
		// Connect to the database.
		$this->innerMysqli->connect($location, $username, $password, "invsys");
		// Let everyone know if this went wrong.
		return ($mysql->errno == 0);
	}
	
	function SelectFromTable(String $table, Array $fields = array(), Array $limits = array(), Int $Start = -1, Int $NumResults = -1): ?Array
	{
		$qfields = ( ($fields === array()) ? "*" : implode(",",$fields) );
		$qlimits = ( ($limits === array()) ? "" : ("WHERE ".implode(",",$limits)));
		$qrange = ( (($Start === $NumResults) && ($Start === -1)) ? "" : "LIMIT {$Start},".($Start+$NumResults));

		$result = $this->innerMysqli->query("SELECT {$qfields} FROM {$table} {$qlimits} {$qrange}");
		
		// Let everyone know if this went wrong.
		if ($mysql->errno != 0) return false;
		
		$columns = array();
		
		$assocdata = $result->fetch_all(MYSQLI_ASSOC);
		if (($qfields == "*") && ($assocdata !== array()))
		{
			// If we chose all fields, we don't have a handy list already...
			$columns = array_keys($assocdata[0]);
		} elseif ($qfields == "*") {
			// Welp. We tried.
			$columns = array("NODATA");
		} else {
			// Oh! We have a list already. Let's use that...
			$columns = $fields;
		}
		return array (
						"columns", $columns,
						"data", $assocdata
					);
	}
	
	function InsertIntoTable(String $table, Array $fieldsvalues) : bool
	{
		
		$cols = implode(",",array_keys($fieldsvalues));
		$values = implode (",",array_values($fieldsvalues));
		$this->innerMysqli->query("INSERT INTO {$table} (($cols)) VALUES(($values))");
		
		// Let everyone know if this went wrong.
		return ($mysql->errno == 0);
	}
}

// Instantiate the default driver.
$db = new DefaultSQLDriver();
?>