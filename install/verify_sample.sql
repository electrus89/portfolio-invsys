-- This should show entity 1 only, the only entity that can log in.
select entities.FullName,entities.Description,entities.Classification,entity_to_user.Username,credentials.Credential,credentials.CredType 
	FROM entities INNER JOIN entity_to_user INNER JOIN credentials 
	ON entities.ID = entity_to_user.EntityID AND entity_to_user.ID = credentials.UserID;
/* Sample var_dump output
   [MySQLDriver::SelectFromMultipleTables()]
   ----------------------
$db->SelectFromMultipleTables(array(
									"entities"       => array ("FullName", "Description", "Classification"),
									"entity_to_user" => array ("Username"),
									"credentials"    => array ("Credential", "CredType")
								), array (
									"entities.ID" => "entity_to_user.EntityID",
									"entity_to_user.ID" => "credentials.UserID"
								)
);
   ----------------------
array(3) {
  ["query"]=>
  string(282) "SELECT entities.FullName,entities.Description,entities.Classification,entity_to_user.Username,credentials.Credential,credentials.CredType FROM entities INNER JOIN entity_to_user INNER JOIN credentials ON entities.ID=entity_to_user.EntityID AND entity_to_user.ID=credentials.UserID  "
  ["columns"]=>
  array(6) {
    [0]=>
    string(8) "FullName"
    [1]=>
    string(11) "Description"
    [2]=>
    string(14) "Classification"
    [3]=>
    string(8) "Username"
    [4]=>
    string(10) "Credential"
    [5]=>
    string(8) "CredType"
  }
  ["data"]=>
  array(1) {
    [0]=>
    array(6) {
      ["FullName"]=>
      string(6) "Sample"
      ["Description"]=>
      string(11) "Sample User"
      ["Classification"]=>
      string(1) "0"
      ["Username"]=>
      string(6) "sample"
      ["Credential"]=>
      string(41) "*E24E4BCE4CC0434C89167102348329385E9EC782"
      ["CredType"]=>
      string(1) "0"
    }
  }
}
*/

-- Entity 1 only has one item checked out....
SELECT * 
	FROM assignments 
	WHERE assignments.AssignedTo = 1
/* Sample var_dump output
   [MySQLDriver::SelectFromTable()]
   ----------------------
$db->SelectFromTable("assignments", "*", array("AssignedTo = 1"));
   ----------------------
array(3) {
  ["query"]=>
  string(47) "SELECT * FROM assignments WHERE AssignedTo = 1 "
  ["columns"]=>
  array(5) {
    [0]=>
    string(2) "ID"
    [1]=>
    string(6) "ItemID"
    [2]=>
    string(10) "AssignedTo"
    [3]=>
    string(10) "AssignedBy"
    [4]=>
    string(6) "Status"
  }
  ["data"]=>
  array(1) {
    [0]=>
    array(5) {
      ["ID"]=>
      string(1) "1"
      ["ItemID"]=>
      string(1) "1"
      ["AssignedTo"]=>
      string(1) "1"
      ["AssignedBy"]=>
      string(1) "1"
      ["Status"]=>
      string(1) "0"
    }
  }
}*/
	
-- ... as does entity 3 ...
SELECT * 
	FROM assignments 
	WHERE AssignedTo = 3;
/* Sample var_dump output
   [MySQLDriver::SelectFromTable()]
   ----------------------
$db->SelectFromTable("assignments", "*", array("AssignedTo = 3"));
   ----------------------
array(3) {
  ["query"]=>
  string(47) "SELECT * FROM assignments WHERE AssignedTo = 3 "
  ["columns"]=>
  array(5) {
    [0]=>
    string(2) "ID"
    [1]=>
    string(6) "ItemID"
    [2]=>
    string(10) "AssignedTo"
    [3]=>
    string(10) "AssignedBy"
    [4]=>
    string(6) "Status"
  }
  ["data"]=>
  array(1) {
    [0]=>
    array(5) {
      ["ID"]=>
      string(1) "2"
      ["ItemID"]=>
      string(1) "2"
      ["AssignedTo"]=>
      string(1) "3"
      ["AssignedBy"]=>
      string(1) "1"
      ["Status"]=>
      string(1) "1"
    }
  }
}
*/

	
-- ... but Entity 1 checked them both out.
SELECT * 
	FROM assignments 
	WHERE assignments.AssignedBy = 1;
/* Sample var_dump output
   [MySQLDriver::SelectFromTable()]
   ----------------------
$db->SelectFromTable("assignments", "*", array("AssignedBy = 1"));
   ----------------------
array(3) {
  ["query"]=>
  string(47) "SELECT * FROM assignments WHERE AssignedBy = 1 "
  ["columns"]=>
  array(5) {
    [0]=>
    string(2) "ID"
    [1]=>
    string(6) "ItemID"
    [2]=>
    string(10) "AssignedTo"
    [3]=>
    string(10) "AssignedBy"
    [4]=>
    string(6) "Status"
  }
  ["data"]=>
  array(2) {
    [0]=>
    array(5) {
      ["ID"]=>
      string(1) "1"
      ["ItemID"]=>
      string(1) "1"
      ["AssignedTo"]=>
      string(1) "1"
      ["AssignedBy"]=>
      string(1) "1"
      ["Status"]=>
      string(1) "0"
    }
    [1]=>
    array(5) {
      ["ID"]=>
      string(1) "2"
      ["ItemID"]=>
      string(1) "2"
      ["AssignedTo"]=>
      string(1) "3"
      ["AssignedBy"]=>
      string(1) "1"
      ["Status"]=>
      string(1) "1"
    }
  }
}
*/
