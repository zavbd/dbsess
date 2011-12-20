<?php
/*
Example of use, $db is your PEAR DB handler
previously created.
*/
require("sessionclass.php");
$session = new session($db);
session_start();

//Now use php sessions as usual

?>
