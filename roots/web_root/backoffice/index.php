<?php
require "../../../conf/app.conf.php";
?><h1>test</h1>
<div>test</div>
<?php
$contents = ob_get_contents();
ob_end_clean();
require "../../application/backoffice_template.php";
?>