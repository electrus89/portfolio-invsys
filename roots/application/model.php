<?php 
/*
	Inventory system
	
	Model Subsystem
	
	This subsystem is used to retrieve the data used in the pages of the application.
	
*/

// Relies on the controller system to be present: The controller contains the 
//   database interface.

require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/controller.php";

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

class DMAssignment {
	use ImportableFromDatabase;
	
	const STATUS_CHECKEDOUT = 1;
	const STATUS_RETURNED = 0;
	const STATUS_INVALID = 65536;
	
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
	}
}

class DMEntity {
	use ImportableFromDatabase;
	
	const CLASSIFICATION_PERSON = 0;
	const CLASSIFICATION_TEAM = 1;
	const CLASSIFICATION_PROJECT = 2;
	const CLASSIFICATION_INVALID = 65536;
	
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
}

class DMEntityToUserRel {
	use ImportableFromDatabase;
	
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
			// Well, we overlooked something.
		}
	}
}

class DMCredentials {
	use ImportableFromDatabase;
	
	const CREDTYPE_PASSWORD = 0;
	const CREDTYPE_INVALID = 65536;
	
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
		global $db, $passseed, $passul;
		$salt = ($this->UserID * $passseed) % $passul;
		
		if ($this->ID === -1)
		{
			$db->InsertIntoTable("credentials", array("UserID" => $this->UserID,
													  "Credential" => ( ($this->CredType == DMCredentials::CREDTYPE_PASSWORD) ? "PASSWORD('{$salt}#{$this->Credential}')" : "'{$this->Credential}'"),
													  "CredType" => $this->CredType));
		} else {
			// Well, we overlooked something.
		}
	}
}

class DMEntityRelationship {
	use ImportableFromDatabase;
	
	const REL_MEMBER = 0;
	const REL_LEADER = 1;
	const REL_INVALID = 65536;
	
	var $ID = -1;
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
			// Well, we overlooked something.
		}
	}
}
?>