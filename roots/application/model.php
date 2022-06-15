<?php 
/*
	Inventory system
	
	Model Subsystem
	
	This subsystem is used to retrieve the data used in the pages of the application.
	
*/

// Relies on the controller system to be present: The controller contains the 
//   database interface.
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/controller2.php";

class DMAssignment {
	const STATUS_CHECKEDOUT = 1;
	const STATUS_RETURNED = 0;
	const STATUS_INVALID = 65536;
}
class DMEntity {
	const CLASSIFICATION_PERSON = 0;
	const CLASSIFICATION_TEAM = 1;
	const CLASSIFICATION_PROJECT = 2;
	const CLASSIFICATION_INVALID = 65536;
}
class DMCredentials {
	const CREDTYPE_PASSWORD = 0;
	const CREDTYPE_INVALID = 65536;
}
class DMEntityRelationship {
	const REL_MEMBER = 0;
	const REL_LEADER = 1;
	const REL_INVALID = 65536;
}

class DataModel
{
	// authenticate_entity(<username>, array("type" => DMCredentials::<constant>, "data" => <credential_data>))
	static function authenticate_entity($username, $credential) : ?Array
	{
		global $db;
		
		$q1 = new SelectQuery;
		$q1->tables 			= array("entity_to_user",
										"credentials");
		$q1->fields 			= array("credentials.UserID");
		$q1->keyrelationships 	= array("entity_to_user.ID" => "credentials.UserID");
		$q1->where				= array("entity_to_user.Username = \"{$username}\"",
										"credentials.CredType = {$credential['type']}");
		
		// Does a user have both an credential of a matching type and a matching username?
		$r1 = $db->Select($q1);
		
		if ($r1->success == false) return null; // If there's an issue, return a negative result, meaning login failure.
		
		$serializer = "";
		// Form the serializer for the credential.
		// The serializer ensures the search for the credential matches what is on the server.
		switch ($credential["type"])
		{
			case DMCredentials::CREDTYPE_PASSWORD:
				$salt = (String)(DMSecurity::CalculatePWSalt((int)($r1["data"][0]["UserID"]))); // Calculate the Salt, then cast it to a String.
				$serializer = "PASSWORD('{$salt}#{$credential['data']}')";
		}
		
		$q2 = new SelectQuery;
		$q2->fields = array("ID", 
						    "EntityID", 
							"Username");
		$q2->tables = array("entity_to_user",
							"credentials",
							"entities");
		$q2->keyrelationships = array("entities.ID" => "entity_to_user.EntityID",
								"entity_to_user.ID" => "credentials.UserID");
		$q2->where = array( "entity_to_user.Username = \"{$username}\"",
							"credentials.Credential = {$serializer}",
							"credentials.CredType = {$credential['type']}");
		$q2->limit["quantity"] = 1;
		if ($r2->success == false) return null; // If there's an issue, return a negative result, meaning login failure.
		if (count($r2->data) == 0) return null; // The user wasn't found.
		return array( "EntityID" => $r2->data[0]["EntityID"],
					  "Username" => $r2->data[0]["Username"],
					  "UserID" =>   $r2->data[0]["ID"] );
	}
	static function checkout_item($username, $credential, $entitytoid, $itemid) : bool
	{
		$authres = authenticate_entity($username, credential);
		if ($authres == null) return false;
		
		$entitybyid = $authres["EntityID"];
		
		$q = new InsertQuery;
		$q->tables = array("audit_entries");
		$q->fields = array("AssignedWhen" => time(),
						   "AssignedTo"   => $entitytoid,
						   "AssignedBy" => $entitybyid,
						   "NewStatus" => DMAssignment::STATUS_CHECKEDOUT,
						   "ItemID" => $itemid);
		return true;
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
				$q2->fields = array("audit_entries.AssignedWhen",
									"audit_entries.AssignedBy",
									"audit_entries.AssignedTo",
									"items.ID",
									"items.ShortName");
				$q2->tables = array("audit_entries", 
									"items");
				$q2->keyrelationships = array("items.ID" => "audit_entries.ItemID");
				$q2->where = array("items.ID = {$datarow['ItemID']}", "audit_entries.AssignedWhen={$datarow['LastAssigned']}", "audit_entries.NewStatus={$coval}");
				$r2 = $db->Select($q2);
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

	static function get_entity_name($entityid) : String
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
	
	static function ensure_current_itemstatus($itemid, $status) : bool
	{
		global $db;
		
		$q = new SelectQuery;
		$q->tables = array("audit_entries");
		$q->fields = array("ItemID", "AssignedWhen", "NewStatus");
		$q->limit["quantity"] = 1;
		$q->groupby["field"] = "ItemID";
		$q->orderby["field"] = "AssignedWhen";
		$q->orderby["direction"] = "DESC";
		$q->where = array( "ItemID = {$itemid}" );
		var_dump ($q);
		$r = $db->Select($q);
		var_dump ($r);
		if (!$r->success) return false;
		if (count($r->data) == 0) return false;
		return ($r->data[0]["NewStatus"] == $status);
	}
}
echo "<pre>";
var_dump(DataModel::ensure_current_itemstatus(2, DMAssignment::STATUS_CHECKEDOUT));
var_dump(DataModel::ensure_current_itemstatus(1, DMAssignment::STATUS_RETURNED));
var_dump(DataModel::ensure_current_itemstatus(3, DMAssignment::STATUS_RETURNED));
echo "</pre>";
class DMSecurity
{
	static function CalculatePWSalt($UserID) : int
	{
		global $passul, $passseed;
	    return ($UserID * $passseed) % $passul;
	}
}
?>