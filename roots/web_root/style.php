<?php
// Essentially a proxy for UIStylesheet::getCSS() in the view subsystem.
require_once "{$_SERVER['DOCUMENT_ROOT']}/../../conf/app.conf.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view.php";

header("Content-Type: text/css");

// Tell the GUI that it's generating a Stylesheet for the kiosk.
$gui = new GUI($colorway_kiosk,"css");

echo $gui -> getUIStylesheet();
?>