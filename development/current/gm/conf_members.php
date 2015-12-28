<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Members Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_POST['submit_form'])
{
	if ($_POST['members_default_limit'] == "" || intval($_POST['members_default_limit']) < 0)
	{
		$members_default_limit = 30;
	}
	else
	{
		$members_default_limit = intval($_POST['members_default_limit']);
	}
	//Update `members_default_limit` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='members_default_limit'", array($members_default_limit));
	
	$setting = getSettings($db);
}

include("./header.php");

echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
echo "<table width=\"100%\">";

echo "<tr><td width=\"40%\">\n";
echo "<b>Default Limit:</b>";
echo "</td><td width=\"60%\">\n";
echo "<input type=\"text\" name=\"members_default_limit\" value=\"" . $setting->members_default_limit . "\" />\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "Determines how many players you want to show on one page of the members list by default.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "</td><td width=\"60%\">\n";
echo "<br /><input type=\"submit\" name=\"submit_form\" value=\"Submit\" />\n";
echo "</td></tr>\n";

echo "</table>\n";
echo "</form>\n";

include("./footer.php");
?>