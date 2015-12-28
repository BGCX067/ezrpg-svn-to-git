<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Hospital Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_POST['submit_form'])
{
	if ($_POST['hospital_rate'] == "" || intval($_POST['hospital_rate']) < 0)
	{
		$hospital_rate = 3;
	}
	else
	{
		$hospital_rate = intval($_POST['hospital_rate']);
	}
	//Update `hospital_rate` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='hospital_rate'", array($hospital_rate));
	
	$setting = getSettings($db);
}

include("./header.php");

echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
echo "<table width=\"100%\">";

echo "<tr><td width=\"40%\">\n";
echo "<b>Hospital Rate:</b>";
echo "</td><td width=\"60%\">\n";
echo "<input type=\"text\" name=\"hospital_rate\" value=\"" . $setting->hospital_rate . "\" />\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "The cost of healing 1 HP.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "</td><td width=\"60%\">\n";
echo "<br /><input type=\"submit\" name=\"submit_form\" value=\"Submit\" />\n";
echo "</td></tr>\n";

echo "</table>\n";
echo "</form>\n";

include("./footer.php");
?>