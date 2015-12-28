<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "General Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_POST['submit_form'])
{
	if ($_POST['general_close_game'] != "yes" && $_POST['general_close_game'] != "no")
	{
		$general_close_game = "no";
	}
	else
	{
		$general_close_game = $_POST['general_close_game'];
	}
	//Update `general_close_game` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='general_close_game'", array($general_close_game));
	
	if ($_POST['general_stat_bar'] != "text" && $_POST['general_stat_bar'] != "image")
	{
		$general_stat_bar = "text";
	}
	else
	{
		$general_stat_bar = $_POST['general_stat_bar'];
	}
	//Update `general_stat_bar` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='general_stat_bar'", array($general_stat_bar));
	
	if ($_POST['general_bar_filetype'] != "PNG" && $_POST['general_bar_filetype'] != "GIF" && $_POST['general_bar_filetype'] != "JPG")
	{
		$general_bar_filetype = "PNG";
	}
	else
	{
		$general_bar_filetype = $_POST['general_bar_filetype'];
	}
	//Update `general_bar_filetype` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='general_bar_filetype'", array($general_bar_filetype));
	
	$setting = getSettings($db);
}

include("./header.php");

echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
echo "<table width=\"100%\">";

echo "<tr><td width=\"40%\">\n";
echo "<b>Close Game:</b>";
echo "</td><td width=\"60%\">\n";
echo "<select name=\"general_close_game\" style=\"width: 150px;\">\n";
echo "<option value=\"yes\"";
echo ($setting->general_close_game == "yes")?" selected=\"selected\"":"";
echo ">Yes</option>\n";
echo "<option value=\"no\"";
echo ($setting->general_close_game == "no")?" selected=\"selected\"":"";
echo ">No</option>\n";
echo "</select>\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "This will close the game and stop people from logging in and playing. Anybody already logged in will be logged out.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "<b>Status Type:</b>";
echo "</td><td width=\"60%\">\n";
echo "<select name=\"general_stat_bar\" style=\"width: 150px;\">\n";
echo "<option value=\"text\"";
echo ($setting->general_stat_bar == "text")?" selected=\"selected\"":"";
echo ">Text</option>\n";
echo "<option value=\"image\"";
echo ($setting->general_stat_bar == "image")?" selected=\"selected\"":"";
echo ">Image</option>\n";
echo "</select>\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "How do you want to show user stats? Choose from text/numbers or an image bar automatically generated.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "<b>Stat Bar Image Type:</b>";
echo "</td><td width=\"60%\">\n";
echo "<select name=\"general_bar_filetype\" style=\"width: 150px;\">\n";
echo "<option value=\"PNG\"";
echo ($setting->general_bar_filetype == "PNG")?" selected=\"selected\"":"";
echo ">PNG</option>\n";
echo "<option value=\"GIF\"";
echo ($setting->general_bar_filetype == "GIF")?" selected=\"selected\"":"";
echo ">GIF</option>\n";
echo "<option value=\"JPG\"";
echo ($setting->general_bar_filetype == "JPG")?" selected=\"selected\"":"";
echo ">JPG</option>\n";
echo "</select>\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "If you chose to use an image bar to show user stats, what file type do you want to use for the image?<br />\n";
echo "PNG - Not supported by older browsers, but has the highest quality and smallest file size.<br />\n";
echo "GIF - Supported by most browsers, good quality, small file size.<br />\n";
echo "JPG - Supported by most browsers, medium quality, large file size.<br />\n";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "</td><td width=\"60%\">\n";
echo "<br /><input type=\"submit\" name=\"submit_form\" value=\"Submit\" />\n";
echo "</td></tr>\n";

echo "</table>\n";
echo "</form>\n";

include("./footer.php");
?>