<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Battle Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_POST['submit_form'])
{
	if ($_POST['battle_min_level'] == "" || intval($_POST['battle_min_level']) < 0)
	{
		$battle_min_level = 3;
	}
	else
	{
		$battle_min_level = intval($_POST['battle_min_level']);
	}
	//Update `battle_min_level` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='battle_min_level'", array($battle_min_level));
	
	if ($_POST['battle_round_limit'] == "" || intval($_POST['battle_round_limit']) < 0)
	{
		$battle_round_limit = 30;
	}
	else
	{
		$battle_round_limit = intval($_POST['battle_round_limit']);
	}
	//Update `battle_round_limit`
	$query = $db->execute("update `settings` set `value`=? where `name`='battle_round_limit'", array($battle_round_limit));
	
	$setting = getSettings($db);
}

include("./header.php");

echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
echo "<table width=\"100%\">";

echo "<tr><td width=\"40%\">\n";
echo "<b>Maximum Level Difference:</b>";
echo "</td><td width=\"60%\">\n";
echo "<input type=\"text\" name=\"battle_min_level\" value=\"" . $setting->battle_min_level . "\" />\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "Determines how much lower the defender's level can be compared to the attacker.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "<b>Maximum Battle Rounds:</b>";
echo "</td><td width=\"60%\">\n";
echo "<input type=\"text\" name=\"battle_round_limit\" value=\"" . $setting->battle_round_limit . "\" />\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "This will limit the maximum number of rounds in a single battle.<br />";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "</td><td width=\"60%\">\n";
echo "<br /><input type=\"submit\" name=\"submit_form\" value=\"Submit\" />\n";
echo "</td></tr>\n";

echo "</table>\n";
echo "</form>\n";

include("./footer.php");
?>