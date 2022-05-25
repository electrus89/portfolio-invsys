<?php
// Selection of the colorways requires this.
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/view.php";
// Connection to database and Selection of Driver requires this.
require_once "{$_SERVER['DOCUMENT_ROOT']}/../application/controller.php";

?><?php

// Configuration of the Inventory Server

// $colorway_*:
//		What colorway should each subapplication use?
//		Should be a member of Colorways class in the View subsystem.
$colorway_backoffice = Colorways::$Black;
$colorway_kiosk = Colorways::$Orange;

// $db
//		Database Driver selection.
// $db->Connect ( <location of server>, <username>, <password> )
//		Database Connection configuration.
$db = new MySQLDriver();
$db->Connect("unix://var/run/mysqld/mysqld.sock", "invsys", "password");

// $passseed = Password Seed for Salts of Passwords in the Database [Salts are per-EntityID]
// $passul = Upper limit value for salts, after which it wraps around.
$passseed = 12093809;
$passul = 453345232113;
?>