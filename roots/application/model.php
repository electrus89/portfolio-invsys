<?php 
/*
	Inventory system
	
	Model Subsystem
	
	This subsystem is used to retrieve the data used in the pages of the application.
	
*/

// Relies on the controller system to be present: The controller contains the 
//   database interface.
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/controller2.php";

/*
// This will import any data object from the database.
trait ImportableFromDatabase {
	function __construct(Array $result)
	{
		foreach ($result as $column => $value)
		{
			// This works because the properties can be addressed this way and 
			//   we name the properties identically to their column names in
			//   the database.
			$this->{$column} = $value;
		}
	}
}

// Item Object
class DMItem {
	use ImportableFromDatabase;
	
	var $ID = -1;
	var $ShortName = "";
	var $Description = "";
	
	// This is a new object, so it'll be set manually.
	function __construct(String $ShortName="", String $Description=""){
		$this->ShortName = $ShortName;
		$this->Description = $Description;
	}
	// Can be imported from database too.

	function pushToDatabase()
	{
		global $db;
		
		if ($this->ID === -1)
		{
			$db->InsertIntoTable("items", array("ShortName" => "'{$this->ShortName}'",
										        "Description" => "'{$this->Description}'"));
		} else {
			// Well, we overlooked something.
		}
	}
}
*/
class DMAssignment {
	//use ImportableFromDatabase;
	
	const STATUS_CHECKEDOUT = 1;
	const STATUS_RETURNED = 0;
	const STATUS_INVALID = 65536;
	/*
	var $ID = -1;
	var $ItemID = -1;
	var $AssignedTo = -1;
	var $AssignedBy = -1;
	var $Status = DMAssignment::STATUS_INVALID;
	
	// This is a new object, so it'll be set manually.
	function __construct(Int $ItemID = -1, Int $AssignedBy = -1, Int $AssignedTo = -1 , Int $Status = DMAssignment::STATUS_INVALID){
		$this->ItemID = $ItemID;
		$this->AssignedBy = $AssignedBy;
		$this->AssignedTo = $AssignedTo;
		$this->Status = $Status;
	}
	// Can be imported from database too.

	function pushToDatabase()
	{
		global $db;
		
		if ($this->ID === -1)
		{
			$db->InsertIntoTable("assignments", array("ItemID" => $this->ItemID,
													  "AssignedBy" => $this->AssignedBy,
													  "AssignedTo" => $this->AssignedTo,
													  "Status" => $this->Status));
		} else {
			// Well, we overlooked something.
		}
	}*/
}

class DMEntity {
	//use ImportableFromDatabase;
	
	const CLASSIFICATION_PERSON = 0;
	const CLASSIFICATION_TEAM = 1;
	const CLASSIFICATION_PROJECT = 2;
	const CLASSIFICATION_INVALID = 65536;
	/*
	var $ID = -1;
	var $FullName = ""; // Clippy McClipface was here, but that affected execution.
	var $Description = "";
	var $Classification = DMEntity::CLASSIFICATION_INVALID;
	
	// This is a new object, so it'll be set manually.
	function __construct(String $FullName="", String $Description="" , Int $Classification = DMEntity::CLASSIFICATION_INVALID){
		$this->FullName = $FullName;
		$this->Description = $Description;
		$this->Classification = $Classification;
	}

	// Can be imported from database too.

	function pushToDatabase()
	{
		global $db;
		
		if ($this->ID === -1)
		{
			$db->InsertIntoTable("entities", array("FullName" => "'{$this->FullName}'",
												   "Description" => "'{$this->Description}'",
												   "Classification" => $this->Classification));
		} else {
			// Well, we overlooked something.
		}
	}
*/
}
/*
class DMEntityToUserRel {
	//use ImportableFromDatabase;
	
	var $ID = -1;
	var $EntityID = -1;
	var $Username = "";
	
	// This is a new object, so it'll be set manually.
	function __construct(Int $EntityID = -1, String $Username = ""){
		$this->EntityID = $EntityID;
		$this->Username = $Username;
	}

	// Can be imported from database too.

	function pushToDatabase()
	{
		global $db;
		
		if ($this->ID === -1)
		{
			$db->InsertIntoTable("entity_to_user", array("EntityID" => $this->EntityID,
														 "Username" => "'{$this->Username}'"));
		} else {
			$db->UpdateTable("credentials", array("EntityMaster" => $this->EntityMaster,
												  "EntitySub" => $this->EntitySub,
												  "Relationship" => $this->Relationship),
										   array("ID" => $this->ID));
		}
	}
}
*/
class DMCredentials {
//	use ImportableFromDatabase;
	
	const CREDTYPE_PASSWORD = 0;
	const CREDTYPE_INVALID = 65536;
/*	
	var $ID = -1;
	var $UserID = -1;
	var $Credential = "";
	var $CredType = DMCredentials::CREDTYPE_INVALID;
	
	// This is a new object, so it'll be set manually.
	function __construct(Int $UserID = -1, String $Credential = "", Int $CredType = DMCredentials::CREDTYPE_INVALID){
		$this->UserID = $UserID;
		$this->Credential = $Credential;
		$this->CredType = $CredType;
	}

	// Can be imported from database too.

	function pushToDatabase()
	{
		// FIXME: This will resalt and rehash the salted/hashed password. 
		global $db, $passseed, $passul;
		$salt = ($this->UserID * $passseed) % $passul;
		
		if ($this->ID === -1)
		{
			$db->InsertIntoTable("credentials", array("UserID" => $this->UserID,
													  "Credential" => ( ($this->CredType == DMCredentials::CREDTYPE_PASSWORD) ? "PASSWORD('{$salt}#{$this->Credential}')" : "'{$this->Credential}'"),
													  "CredType" => $this->CredType));
		} else {
			$db->UpdateTable("credentials", array("UserID" => $this->UserID,
													  "Credential" => ( ($this->CredType == DMCredentials::CREDTYPE_PASSWORD) ? "PASSWORD('{$salt}#{$this->Credential}')" : "'{$this->Credential}'"),
													  "CredType" => $this->CredType),
										   array("ID" => $this->ID));
		}
	}
*/
}

class DMEntityRelationship {
//	use ImportableFromDatabase;
	
	const REL_MEMBER = 0;
	const REL_LEADER = 1;
	const REL_INVALID = 65536;
	
/*	var $ID = -1;
	var $EntityMaster = -1;
	var $EntitySub = -1;
	var $Relationship = DMEntityRelationship::REL_INVALID;
	
	// This is a new object, so it'll be set manually.
	function __construct(Int $EntityMaster =-1, Int $EntitySub = -1, Int $Relationship= DMEntityRelationship::REL_INVALID){
		$this->EntityMaster = $EntityMaster;
		$this->EntitySub = $EntitySub;
		$this->Relationship = $Relationship;
	}

	// Can be imported from database too.

	function pushToDatabase()
	{
		global $db;
		
		if ($this->ID === -1)
		{
			$db->InsertIntoTable("credentials", array("EntityMaster" => $this->EntityMaster,
													  "EntitySub" => $this->EntitySub,
													  "Relationship" => $this->Relationship));
		} else {
			$db->UpdateTable("credentials",array("EntityMaster" => $this->EntityMaster,
												 "EntitySub" => $this->EntitySub,
												 "Relationship" => $this->Relationship),
										   array("ID" => $this->ID));
		}
	}
*/
}

class DataModel
{
	// authenticate_entity(<username>, array("type" => DMCredentials::<constant>, "data" => <credential_data>))
	static function authenticate_entity($username, $credential) : ?Array
	{
		global $db;
		
		// Does a user have both an credential of a matching type and a matching username?
		$r1 = $db->SelectFromMultipleTables( array(	"entity_to_user" => array(),
													"credentials" => array("UserID")),
											 array( "entity_to_user.ID" => "credentials.UserID"),
											 array( "entity_to_user.Username = \"{$username}\"",
													"credentials.CredType = {$credential['type']}"));
		
		if ($r1 == null) return null; // If there's an issue, return a negative result, meaning login failure.
		
		$serializer = "";
		// Form the serializer for the credential.
		// The serializer ensures the search for the credential matches what is on the server.
		switch ($credential["type"])
		{
			case DMCredentials::CREDTYPE_PASSWORD:
				$salt = (String)(DMSecurity::CalculatePWSalt((int)($r1["data"][0]["UserID"]))); // Calculate the Salt, then cast it to a String.
				$serializer = "PASSWORD('{$salt}#{$credential['data']}')";
		}
		
		$r2 = $db->SelectFromMultipleTables(array( "entity_to_user" => array("ID", "EntityID", "Username"), // Select the five relevant fields in three tables...
											 "credentials" => array(),
											 "entities" => array()),								  // No fields selected but we should still INNER JOIN this one.
									  array( "entities.ID" => "entity_to_user.EntityID",			  // Two primary keys bind these three tables.
									         "entity_to_user.ID" => "credentials.UserID"),
									  array("entity_to_user.Username = \"{$username}\"",
										    "credentials.Credential = {$serializer}",
											"credentials.CredType = {$credential['type']}"));		
		if ($r2 == null) return null; // If there's an issue, return a negative result, meaning login failure.
		return array( "EntityID" => $r2["data"][0]["EntityID"],
					  "Username" => $r2["data"][0]["Username"],
					  "UserID" =>   $r2["data"][0]["ID"] );
	}
	static function checkout_item($username, $credential, $entitytoid, $itemid) : ?Array
	{
	}

	static function list_entities_by_classification() : ?Array
	{
		global $db;		
		$entities = array("persons" => array(), "teams" => array(), "projects" => array());
		$q = new SelectQuery;
		$q->fields = array("ID","FullName","Classification");
		$q->tables = array("entities");
		$r = $db->Select($q);
		if (!$r->success) return null;
		foreach ($r->data as $datarow)
		{
			switch ($datarow["Classification"])
			{
				case DMEntity::CLASSIFICATION_PERSON:	$entities["persons"][] = array ("ID" => $datarow["ID"], "FullName" => $datarow["FullName"]);
														break;
				case DMEntity::CLASSIFICATION_PROJECT:	$entities["projects"][] = array ("ID" => $datarow["ID"], "FullName" => $datarow["FullName"]);
														break;
				case DMEntity::CLASSIFICATION_TEAM: 	$entities["teams"][] = array ("ID" => $datarow["ID"], "FullName" => $datarow["FullName"]);
														break;
			}
		}
		return $entities;
	}
	static function list_items(?bool $is_checked_out) : ?Array
	{
		global $db;
		
		if ($is_checked_out === null)
		{
			$q = new SelectQuery;
			$q->fields = array("ID","ShortName");
			$q->tables = array("items");
			$r1 = $db->Select($q);
			if ($r1->success == false){
				return null;
			}
			return $r1->data;
		} else {
			$coval = ($is_checked_out) ? DMAssignment::STATUS_CHECKEDOUT : DMAssignment::STATUS_RETURNED;
						
			$q = new SelectQuery;
			$q->fields = array("ItemID","max(AssignedWhen) as LastAssigned");
			$q->tables = array("audit_entries");
			$q->groupby["field"] = "ItemID";
			$r1 = $db->Select($q);
			
			if ($r1->success == false){
				return null;
			}

			$rx = new QueryResult;

			foreach ($r1->data as $datarow)
			{
				$q2 = new SelectQuery;
				$q2->fields = array("audit_entries.AssignedWhen","audit_entries.AssignedBy","audit_entries.AssignedTo","items.ID","items.ShortName");
				$q2->tables = array("audit_entries", "items");
				$q2->keyrelationships = array("items.ID" => "audit_entries.ItemID");
				$q2->where = array("items.ID = {$datarow['ItemID']}", "audit_entries.AssignedWhen={$datarow['LastAssigned']}", "audit_entries.NewStatus={$coval}");
				$r2 = $db->Select($q2);
				//var_dump((String)$q2);
				if ($r2->success == false){
					return null;
				}	
				$rx->query .= "{$r2->query};\n";
				if (count($r2->data) == 0) continue;
				$rx->data[] = $r2->data[0];
			}
			return $rx->data;
		}
	}

	function get_entity_name($entityid) : String
	{
		global $db;
		
		$q = new SelectQuery;
		$q->fields = array("FullName");
		$q->tables = array("entities");
		$q->where = array("ID = {$entityid}");
		$q->limit["quantity"] = 1;
		$r = $db->Select($q);
		if (!$r->success) return "#ERR#";
		if (count($r->data) == 1)
			return $r->data[0]["FullName"];
		else
			return "???";
	}
}

class DMSecurity
{
	static function CalculatePWSalt($UserID) : int
	{
		global $passul, $passseed;
	    return ($UserID * $passseed) % $passul;
	}
}
?>