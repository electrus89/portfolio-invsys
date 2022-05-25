<?php
// Selection of the colorways requires this.
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view.php";

?><?php

// Configuration of the Inventory Server

// $colorway_*:
//		What colorway should each subapplication use?
//		Should be a member of Colorways class in the View subsystem.
$colorway_backoffice = Colorways::$Black;
$colorway_kiosk = Colorways::$Orange;
?>