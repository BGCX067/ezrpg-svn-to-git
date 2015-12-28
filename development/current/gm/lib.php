<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

session_start();

include("../config.php");

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
	include("../languages/en.php"); //Include default language file
}


//Function to check if user is logged in, and if so, return user data as an object
function check_user($secret_key, &$db)
{
	if (!isset($_SESSION['userid']) || !isset($_SESSION['hash']))
	{
		header("Location: ../index.php");
		exit;
	}
	else
	{
		$check = sha1($_SESSION['userid'] . $_SERVER['REMOTE_ADDR'] . $secret_key);
		if ($check != $_SESSION['hash'])
		{
			session_unset();
			session_destroy();
			header("Location: ../logout.php");
			exit;
		}
		else
		{
			$query = $db->execute("select * from `players` where `id`=?", array($_SESSION['userid']));
			$userarray = $query->fetchrow();
			if ($query->recordcount() == 0)
			{
				session_unset();
				session_destroy();
				header("Location: ../logout.php");
				exit;
			}
			foreach($userarray as $key=>$value)
			{
				$user->$key = $value;
			}
			return $user;
		}
	}
}

$player = check_user($secret_key, $db);

//Function to check player's GM rank
function check_permissions(&$player, $rank)
{
	if ($player->gm_rank < $rank)
	{
		return 0;
	}
	else
	{
		return 1;
	}
}

//Insert a log message into the error log
function errorlog($msg, &$db)
{
	$insert['msg'] = $msg;
	$insert['time'] = time();
	$query = $db->autoexecute('log_errors', $insert, 'INSERT');
}

//Insert a log message into the GM log
function gmlog($msg, &$db)
{
	$insert['msg'] = $msg;
	$insert['time'] = time();
	$query = $db->autoexecute('log_gm', $insert, 'INSERT');
}


//Get all settings variables
function getSettings(&$db)
{
	$query = $db->execute("select `name`, `value` from `settings`");
	while ($set = $query->fetchrow())
	{
		$setting->$set['name'] = $set['value'];
	}
	return $setting;
}
$setting = getSettings($db);


//Get the player's IP address
$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

//Check that the player has the appropriate permission of a GM rank above 20
if (check_permissions($player, 21) == 0)
{
	header("Location: ../logout.php");
	exit;
}
?>