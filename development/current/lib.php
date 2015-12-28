<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://www.ezrpgproject.com    */
/*************************************/

session_start();

include("./config.php");
include("./lib/functions.php");
include("./lib/class_bar.php");

include('adodb/adodb.inc.php'); //Include adodb files
$db = &ADONewConnection('mysql'); //Connect to database
$db->Connect($config_server, $config_username, $config_password, $config_database); //Select table

$db->SetFetchMode(ADODB_FETCH_ASSOC); //Fetch associative arrays
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //Fetch associative arrays
//$db->debug = true; //Debug


//Include language file
$language_include = "./languages/" . $config_language . ".php"; //Location of language files
if (file_exists($language_include))
{
	include($language_include); //Include language file specified in the config file
}
else
{
	include("./languages/en.php"); //Include default language file
}

//Get all settings variables
$query = $db->execute("select `name`, `value` from `settings`");
while ($set = $query->fetchrow())
{
	$setting->$set['name'] = $set['value'];
}

//Get the player's IP address
$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
?>