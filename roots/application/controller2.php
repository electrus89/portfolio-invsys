<?php
abstract class Database
{
	abstract function __construct(Array $config);
	abstract function Select(SelectQuery $query) : QueryResult;
	abstract function Update(UpdateQuery $query) : QueryResult;
	abstract function Insert(InsertQuery $query) : QueryResult;
}

class QueryResult
{
	public $success = false;	// Successful?
	public $query;				// Query or queries used for this result
	public $type;				// Result type: Select, Update, Insert
	public $data;				// For select queries, the data selected.
}

class SelectQuery
{
	public $fields = array();
	public $tables = array();
	public $orderby = array("field"=>"",
							"direction"=>"");
	public $groupby = array("field"=>"");
	public $where = array();
	public $limit = array("offset" => 0,
						  "quantity" => 0);
						  
	public function __toString()
	{
		$fields = implode(",", $this->fields);
		$tables = implode(" INNER JOIN ", $this->tables);
		if ($this->orderby["field"] === "") {
			$orderby = "";
		} elseif (($this->orderby["field"] !== "") && ($this->orderby["direction"] === "")) {
			$orderby = " ORDER BY {$this->orderby['field']}";
		} else {
			$orderby = " ORDER BY {$this->orderby['field']} {$this->orderby['direction']}";
		}
		$limit = " LIMIT {$this->limit['quantity']}, {$this->limit['offset']}";
		if (count($this->where) != 0) {
			$where = " WHERE ".implode(" AND ", $this->where);
		} else {
			$where = "";
		}
		return "SELECT {$fields} FROM {$tables}{$where}{$orderby}{$limit}"; 
	}
}

class MySQLDatabase extends Database
{
	private $conn;			// This is the connection object.
	
	// This handles connecting to the database server.
	function __construct(Array $config)
	{
		if (array_key_exists("mysql", $config)
		{
			switch ($config["mysql"]["conntype"])
			{
				case "socket":	$this->conn = new mysqli("",$config["mysql"]["username"],$config["mysql"]["password"],$config["mysql"]["database"],0,$config["mysql"]["socket"]);
								break;
				case "port":	$this->conn = new mysqli($config["mysql"]["server"],$config["mysql"]["username"],$config["mysql"]["password"],$config["mysql"]["database"],$config["mysql"]["port"]);
								break;
			}
		} else {
			die ("There is no MySQL configuration defined.");
		}
	}
	
    function Select(SelectQuery $query) : QueryResult
	{
		/*
		$result = $this->innerMysqli->query($q = "SELECT {$qfields} FROM {$table} {$qlimits} {$qrange} {$qgroupby}");
		
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
					);*/
	}
	
	function Update(UpdateQuery $query) : QueryResult
	{
	}
	
	function Insert(InsertQuery $query) : QueryResult
	{
	}
}
?>