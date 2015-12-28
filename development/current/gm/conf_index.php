<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Index Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_POST['submit_form'])
{
	if ($_POST['index_log_ip'] != "yes" && $_POST['index_log_ip'] != "no")
	{
		$index_log_ip = "no";
	}
	else
	{
		$index_log_ip = $_POST['index_log_ip'];
	}
	//Update `index_log_ip` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='index_log_ip'", array($index_log_ip));
	
	if ($_POST['index_log_error'] != "yes" && $_POST['index_log_error'] != "no")
	{
		$index_log_error = "no";
	}
	else
	{
		$index_log_error = $_POST['index_log_error'];
	}
	//Update `index_log_error` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='index_log_error'", array($index_log_error));
	
	$setting = getSettings($db);
}

include("./header.php");

echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
echo "<table width=\"100%\">";

echo "<tr><td width=\"40%\">\n";
echo "<b>Log IP:</b>";
echo "</td><td width=\"60%\">\n";
echo "<select name=\"index_log_ip\" style=\"width: 150px;\">\n";
echo "<option value=\"yes\"";
echo ($setting->index_log_ip == "yes")?" selected=\"selected\"":"";
echo ">Yes</option>\n";
echo "<option value=\"no\"";
echo ($setting->index_log_ip == "no")?" selected=\"selected\"":"";
echo ">No</option>\n";
echo "</select>\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "Select yes if you want to log users who play with the same IP.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "<b>Log Errors:</b>";
echo "</td><td width=\"60%\">\n";
echo "<select name=\"index_log_error\" style=\"width: 150px;\">\n";
echo "<option value=\"yes\"";
echo ($setting->index_log_error == "yes")?" selected=\"selected\"":"";
echo ">Yes</option>\n";
echo "<option value=\"no\"";
echo ($setting->index_log_error == "no")?" selected=\"selected\"":"";
echo ">No</option>\n";
echo "</select>\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "Select yes if you want to log users who type in the wrong username'password combination.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "</td><td width=\"60%\">\n";
echo "<br /><input type=\"submit\" name=\"submit_form\" value=\"Submit\" />\n";
echo "</td></tr>\n";

echo "</table>\n";
echo "</form>\n";

include("./footer.php");
?>