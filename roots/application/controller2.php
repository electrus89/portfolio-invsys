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
						  "quantity" => -1);
	public $keyrelationships = array();
						  
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
		if ($this->groupby["field"] === "") {
			$groupby = "";
		} else {
			$groupby = " GROUP BY {$this->groupby['field']}";
		}
		if ($this->limit["quantity"] > -1) {
			$limit = " LIMIT {$this->limit['quantity']} OFFSET {$this->limit['offset']}";
		} else {
			$limit = "";
		}
		if (count($this->where) != 0) {
			$where = " WHERE ".implode(" AND ", $this->where);
		} else {
			$where = "";
		}
		if (count($this->keyrelationships) > 0) {
			$reltemp = array();
			foreach ($this->keyrelationships as $key => $fld)
				$reltemp[] = "$key = $fld";
			
			$keyrel = " ON ".implode(" AND ", $reltemp);
		} else {
			$keyrel = "";
		}
		
		return "SELECT {$fields} FROM {$tables}{$keyrel}{$where}{$groupby}{$orderby}{$limit}"; 
	}
}

class MySQLDatabase extends Database
{
	private $conn;			// This is the connection object.
	
	// This handles connecting to the database server.
	function __construct(Array $config)
	{
		if (array_key_exists("mysql", $config))
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
		$result = $this->conn->query($query);
		$rtn = new QueryResult;
		if ($this->conn->errno != 0) {
			//echo"<pre>";var_dump((String)$query);var_dump($rtn);echo"</pre>";
			return $rtn;
		}
		$rtn->success = true;
		$rtn->query = (String)$query;
		$rtn->data = $result->fetch_all(MYSQLI_ASSOC);
		$rtn->type = "SELECT";
		//echo"<pre>";var_dump($rtn);echo"</pre>";
		return $rtn;		
	}
	
	function Update(UpdateQuery $query) : QueryResult
	{
	}
	
	function Insert(InsertQuery $query) : QueryResult
	{
	}
}
?>