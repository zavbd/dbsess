<?php
require_once 'DB.php';          // You know you're gonna need this
                                // Imports all the PEAR::DB classes, static
                                // methods, etc...

                                // Get the connection variables:
require_once 'db_config.php';
require("sessionclass.php");

$dsn = $DB_dbType . "://"       // Build a DSN string (Data Source Name)
        . $DB_user . ":"        // Required by DB::connect()
        . $DB_pass . "@" 
        . $DB_host . "/" 
        . $DB_dbName;
//$db = new DB();
//$db->connect($dsn);


$db = @DB::connect($dsn, TRUE);  // Creates a database connection object in $db
                                // or, a database error object if it went wrong.
                                // The boolean specifies this is a persistent
                                // connection like mysql_pconnect(), it
                                // defaults to FALSE.
/*
if (@DB::isError($db)) {         // Check whether the object is a connection or
                                // an error.
    die($db->getMessage());     // Print out a message and exit if it's 
                                // an error object.
}*/
$session_sql = "SELECT * FROM sessions WHERE id = '123'";
$res = $db->query($session_sql);
print_r($db->fetchAll($res));
//$session = new session($db);
//session_id(sha1(mt_rand()));
//session_start();
//$_SESSION['user_id'] = 'zulfiker';
// Do all sorts of stuff with your database connection here.

//$db->disconnect();
?>