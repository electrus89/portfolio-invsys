<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view.php";
// Tell the GUI it's creating a kiosk HTML page.
$gui = new GUI($colorway_kiosk, "html");

$gui->documentModel->navigation = array(
											"Check out" => "./",
											"Where is it?" => "./where.php"
										);
?>