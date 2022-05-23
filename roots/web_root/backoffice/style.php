<?php
header("Content-Type: text/css");
?>/*
Stylesheet for Inventory Backoffice App
*/
body {
	background: #CCC;
	margin: 0px;
}

div#heading {
	background: #E35B13;
	padding: 2px 0px 2px 0px;
	height: 34px;
}

div#heading img#logo {
	height: 32px;
	width: 32px;
	background: black;
	position: relative;
	bottom: -2px;
	left: 2px;
}

div#heading ul#menu {
    display: inline;
    padding: 0px;
}

div#heading ul#menu li {
    display: inline;
	margin: 0px;
}

div#heading ul#menu li a {
	color: white;
	text-decoration: none;
	font-family: sans-serif;
	padding: 4px;
	position: relative;
	bottom: 4px;
}

div#heading ul#menu li a:hover {
	background: #000;
}

div#footing div#copyright {
	font-family: sans-serif;
	font-size: 10pt;
}

div div.ui-fade {
	height: 10px;
	background-color: #FFF;
	display: block;
}

div div.ui-fade#uif-h {
	background-image: linear-gradient(#AAA,#FFF);
}

div div.ui-fade#uif-f {
	background-image: linear-gradient(#FFF,#CCC);
}

div#page {
	background: #FFF;
}

div#page div#content {
	margin: 0px 4px 0px 4px;
	font-family: sans-serif;
}

div#content {
	padding: 5px;
}

h1, h2, h3, h4, h5 { 
	margin: 3px 0px 3px 0px;
}