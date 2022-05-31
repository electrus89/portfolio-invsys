<?php
/*
	Inventory system
	
	Controller Subsystem
	
	Contains lower level logic for the pages and other subsystems.	
*/

// Basic model for the Database Driver, present so the application can adapt to other database servers.
interface DatabaseDriver {
	// SelectFromTable ( <table name>, array ( <field1>, ... , <fieldn> ), ( <limit: eg "ID = 1">, ..., <limit>), <Starting result, like 0>, <Result count, like 10>) -> success?
	function SelectFromTable(String $table, $fields = array(), Array $limits = array(), Int $Start = -1, Int $NumResults = -1): ?Array;
	// InsertIntoTable ( <table name>, array ( <key1> => <value1>,
	//										   ...,
	//										   <keyn> => <valuen>) -> success?
	function InsertIntoTable(String $table, Array $fieldsvalues) : bool;
	// SelectFromMultipleTables ( array( <tablespec> => array( <fieldspec>, ..., <fieldspec>),
	//							  ... , 
	//							  array( <tablespec> => array( <fieldspec>, ..., <fieldspec>), array ( <primarykey> => <fieldinothertable>,
	//																						           ...,
	//																								   <primarykey> => <fieldinothertable>), array(<limit>,...), <starting_result>, <resultcount>)
	function SelectFromMultipleTables(Array $fieldspec, Array $keyequiv, Array $limits = array(), Int $Start = -1, Int $NumResults = -1) : ?Array;
}

// Dummy, Default Driver.
class DefaultSQLDriver implements DatabaseDriver {

	function SelectFromTable(String $table, $fields = array(), Array $limits = array(), Int $Start = -1, Int $NumResults = -1): ?Array
	{
		return false;
	}
	
	function InsertIntoTable(String $table, Array $fieldsvalues) : bool
	{
		return false;
	}

	function SelectFromMultipleTables(Array $fieldspec, Array $keyequiv, Array $limits = array(), Int $Start = -1, Int $NumResults = -1) : ?Array
	{
		return false;
	}
}

// Driver for MySQL and MariaDB.
class MySQLDriver implements DatabaseDriver {
	var $innerMysqli;
	
	function __construct($location,$username,$password)
	{
		//$this->innerMysqli = new mysqli();
		// Connect to the database.
		if ($location[0] == "/")
		{
			// This is a socket.
			$this->innerMysqli = new mysqli("",$username,$password, "invsys", 0, $location);
		} else {
			// This is over TCP/IP
			$parts = explode(":",$location,2);
			if (count($parts) == 1)
			{
				// There's only a hostname.
				$this->innerMysqli = new mysqli($parts[0],$username,$password);
			} else {
				// There's a hostname and port.
				$this->innerMysqli = new mysqli($parts[0],$username,$password,$parts[1]);
			}
		}
	}
	
	function SelectFromTable(String $table, $fields = array(), Array $limits = array(), Int $Start = -1, Int $NumResults = -1): ?Array
	{
		$qfields = ( ($fields === array()) ? implode(",",$fields) : "*" );
		$qlimits = ( ($limits === array()) ? "" : ("WHERE ".implode(",",$limits)));
		$qrange = ( (($Start === $NumResults) && ($Start === -1)) ? "" : "LIMIT {$Start},".($Start+$NumResults));

		$result = $this->innerMysqli->query($q = "SELECT {$qfields} FROM {$table} {$qlimits} {$qrange}");
		
		// Let everyone know if this went wrong.
		if ($this->innerMysqli->errno != 0) return null;
		
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
						"query" => $q,
						"columns" => $columns,
						"data" => $assocdata
					);
	}
	
	function InsertIntoTable(String $table, Array $fieldsvalues) : bool
	{
		
		$cols = implode(",",array_keys($fieldsvalues));
		$values = implode (",",array_values($fieldsvalues));
		$this->innerMysqli->query("INSERT INTO {$table} (($cols)) VALUES(($values))");
		
		// Let everyone know if this went wrong.
		return ($this->innerMysqli->errno == 0);
	}
	
	function SelectFromMultipleTables(Array $fieldspec, Array $keyequiv, Array $limits = array(), Int $Start = -1, Int $NumResults = -1) : ?Array
	{
		$qlimits = ( ($limits === array()) ? "" : ("WHERE ".implode(",",$limits)));
		$qrange = ( (($Start === $NumResults) && ($Start === -1)) ? "" : "LIMIT {$Start},".($Start+$NumResults));
		$qtables = implode(" INNER JOIN ",array_keys($fieldspec));
		
		$equiv = array();
		
		foreach ($keyequiv as $prikey => $keyref)
		{
			$equiv[] = implode("=",array($prikey,$keyref));
		}
		
		$qequiv = implode(" AND ", $equiv);

		$fields = array();
		foreach ($fieldspec as $ctable => $cfields)
		{
			foreach ($cfields as $cfield)
			{
				$fields[] = "{$ctable}.{$cfield}";
			}
		}
		$qfields = implode(",",$fields);

		$result = $this->innerMysqli->query($q = "SELECT {$qfields} FROM {$qtables} ON {$qequiv} {$qlimits} {$qrange}");
		
		// Let everyone know if this went wrong.
		if ($this->innerMysqli->errno != 0) return null;
		
		$columns = array();
		foreach ($fields as $field)
		{
			// The result columns will be without their table names.
			$fieldptarr = explode(".",$field);
			$columns[] = $fieldptarr[1];
		}
		
		$assocdata = $result->fetch_all(MYSQLI_ASSOC);
		
		return array (
						"query" => "{$q}",
						/*"columns_with_tables", $fields,*/
						"columns"=> $columns,
						"data"=> $assocdata
					);
	}
}

// Instantiate the default driver.
$db = new DefaultSQLDriver();
?>