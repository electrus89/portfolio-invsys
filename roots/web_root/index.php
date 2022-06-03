<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/../../conf/app.conf.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/controller.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/model.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view/kiosk.php";
//require "../../application/core.php";


// Tell the GUI the title of the page.
$gui->documentModel->page_title = "Check Out";


ob_start();

?>
<h1>Check Out</h1>
<form action="perform-checkout.php" method="POST">
	<div> 
		<div>Username: <input type="text" name="username"/></div>
		<div>Password: <input type="password" name="password"/></div>
	</div>
	<div>
		is checking out... 
		<?php
		$itemlist = DataModel::list_items(false);
		?><div>Item: <select name="itemid"><?php
		foreach ($itemlist as $item)
		{
			?><option value="<?=$item['ID']?>">#<?=$item['ID']?>: <?=$item['ShortName']?></option><?php
		}?></select></div>
		<div>For: <select name="assignto">
		<?php
		$entitieslist = DataModel::list_entities_by_classification();
		foreach ( array(
							"persons" => array ("caption" => "Persons", "list" => $entitieslist["persons"]),
							"projects" => array ("caption" => "Projects", "list" => $entitieslist["projects"]),
							"teams" => array("caption" => "Teams","list" => $entitieslist["teams"])
					) as $group => $data )
					{
						?><option disabled><?=$data["caption"]?></option><?php
						foreach ($data["list"] as $dataitem)
						{
							?><option value="<?=$dataitem['ID']?>">#<?=$dataitem['ID']?>: <?=$dataitem["FullName"]?></option><?php
						}
					}
		?></select></div>
	</div>
	<input type="submit"/>
</form><?php

// Grab the content of the output buffer and clean the buffer, ending the buffering.
$gui->documentModel->content = ob_get_contents();
ob_end_clean();

echo $gui -> getUIDocument();
?>

