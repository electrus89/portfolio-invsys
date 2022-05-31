<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/../../conf/app.conf.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/controller.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/model.php";
//require "../../application/core.php";

// Tell the GUI it's creating a kiosk HTML page.
$gui = new GUI($colorway_kiosk, "html");

// Tell the GUI the title of the page.
$gui->documentModel->page_title = "Welcome!";

ob_start();

?>

<?php
phpinfo();
?>

<?php

// Grab the content of the output buffer and clean the buffer, ending the buffering.
$gui->documentModel->content = ob_get_contents();
ob_end_clean();

// Display the HTML document in one fell swoop. Easy! (But only if you don't look behind the curtain...)
echo $gui -> getUIDocument();
?>

