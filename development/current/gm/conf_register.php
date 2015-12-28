<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Register Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_POST['submit_form'])
{
	if ($_POST['register_status'] != "closed" && $_POST['register_status'] != "open")
	{
		$register_status = "open";
	}
	else
	{
		$register_status = $_POST['register_status'];
	}
	//Update `register_status` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='register_status'", array($register_status));
	
	$setting = getSettings($db);
}

include("./header.php");

echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
echo "<table width=\"100%\">";

echo "<tr><td width=\"40%\">\n";
echo "<b>Registration Status:</b>";
echo "</td><td width=\"60%\">\n";
echo "<select name=\"register_status\" style=\"width: 170px;\">";
echo "<option value=\"open\"";
echo ($setting->register_status == 'open')?" selected=\"selected\"":"";
echo ">Open</option>\n";
echo "<option value=\"closed\"";
echo ($setting->register_status == 'closed')?" selected=\"selected\"":"";
echo ">Closed</option>\n";
echo "</select>\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "Choose whether new visitors can register or not.<br />";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "</td><td width=\"60%\">\n";
echo "<br /><input type=\"submit\" name=\"submit_form\" value=\"Submit\" />\n";
echo "</td></tr>\n";

echo "</table>\n";
echo "</form>\n";

include("./footer.php");
?>