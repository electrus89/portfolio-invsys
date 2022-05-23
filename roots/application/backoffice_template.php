<html>
        <head>
                <title>$$$ - Inventory System</title>
		<link rel="stylesheet" href="style.php" type="text/css"></link>
        </head>
        <body>
		<div id="heading">
			<img id="logo"/>
			<ul id="menu">
				<li><a href="./">Dashboard</a></li>
			</ul>
		</div>
		<div id="page">
			<div class="ui-fade" id="uif-h"></div>
			<div id="content">
<?php
	print $contents;
?>
			</div>
			<div class="ui-fade" id="uif-f"></div>
		</div>
		<div id="footing">
			<div id="copyright">Sample application utilizing MariaDB, PHP-FPM, and Nginx developed by Joshua Washburn during May 2022</div>
		</div>
        </body>
</html>

