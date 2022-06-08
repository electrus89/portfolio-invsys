<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/../../conf/app.conf.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/controller2.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/model.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view/kiosk.php";

// Tell the GUI the title of the page.
$gui->documentModel->page_title = "Where Is It?";

ob_start();?>
<h1>Where Is It?</h1>
<?php
foreach (array(	"Checked Out" => true, "Returned" => false ) as $title => $status) 
{
?>
<h2><?=$title?></h2>
<table>
	<tr>
		<td>Item</td>
		<td>As of</td>
		<td>Assigned to</td>
		<td>Performed by</td>
	</tr>
	<?php
	$ilist = DataModel::list_items($status);
	//var_dump($ilist);
	foreach ($ilist as $i) {?><tr>
		<td>#<?=$i["ID"]?>: <?=$i["ShortName"]?></td>
		<td><?=date("d M Y h:m:s", $i["AssignedWhen"])?></td>
		<td><?php if ($i["AssignedTo"] == -1) {echo "None";} else { echo "{$i['AssignedTo']}: ???";}?></td>
		<td>#<?=$i["AssignedBy"]?>: <?=DataModel::get_entity_name($i["AssignedBy"])?></td>
	</tr><?php } ?>
</table><?php
}

// Grab the content of the output buffer and clean the buffer, ending the buffering.
$gui->documentModel->content = ob_get_contents();
ob_end_clean();

echo $gui -> getUIDocument();
?>