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
	}
	
	function Update(UpdateQuery $query) : QueryResult
	{
	}
	
	function Insert(InsertQuery $query) : QueryResult
	{
	}
}
?>