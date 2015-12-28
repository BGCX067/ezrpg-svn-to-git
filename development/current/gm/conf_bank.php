<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Bank Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_POST['submit_form'])
{
	if ($_POST['bank_interest_rate'] == "" || intval($_POST['bank_interest_rate']) < 0)
	{
		$bank_interest_rate = 0;
	}
	else
	{
		$bank_interest_rate = intval($_POST['bank_interest_rate']);
	}
	//Update `bank_interest_rate` in settings table
	$query = $db->execute("update `settings` set `value`=? where `name`='bank_interest_rate'", array($bank_interest_rate));
	
	if ($_POST['bank_limit_type'] != 'fixed' && $_POST['bank_limit_type'] != 'variable' && $_POST['bank_limit_type'] != 'unlimited')
	{
		$bank_limit_type = "unlimited";
	}
	else
	{
		$bank_limit_type = $_POST['bank_limit_type'];
	}
	//Update `bank_limit_type`
	$query = $db->execute("update `settings` set `value`=? where `name`='bank_limit_type'", array($bank_limit_type));
	
	if ($_POST['bank_limit'] == "" || intval($_POST['bank_limit']) < 0)
	{
		$bank_limit = 5000;
	}
	else
	{
		$bank_limit = intval($_POST['bank_limit']);
	}
	//Update `bank_limit`
	$query = $db->execute("update `settings` set `value`=? where `name`='bank_limit'", array($bank_limit));
	
	$setting = getSettings($db);
}

include("./header.php");

echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
echo "<table width=\"100%\">";

echo "<tr><td width=\"40%\">\n";
echo "<b>Interest Rate (%):</b>";
echo "</td><td width=\"60%\">\n";
echo "<input type=\"text\" name=\"bank_interest_rate\" value=\"" . $setting->bank_interest_rate . "\" />\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "The interest rate affects how much a player can get from the bank after each reset.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "<b>Limit Type:</b>";
echo "</td><td width=\"60%\">\n";
echo "<select name=\"bank_limit_type\" style=\"width: 170px;\">";
echo "<option value=\"fixed\"";
echo ($setting->bank_limit_type == 'fixed')?" selected=\"selected\"":"";
echo ">Fixed amount</option>\n";
echo "<option value=\"variable\"";
echo ($setting->bank_limit_type == 'variable')?" selected=\"selected\"":"";
echo ">Variable amount</option>\n";
echo "<option value=\"unlimited\"";
echo ($setting->bank_limit_type == 'unlimited')?" selected=\"selected\"":"";
echo ">Unlimited</option>\n";
echo "</select>\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "Choose what type of limit is placed on a player's bank account.<br />";
echo "Fixed - A set amount that a player cannot go above.<br />";
echo "Variable - Higher level players can deposit more.<br />";
echo "Unlimited - No limit is placed.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "<b>Bank Limit:</b>";
echo "</td><td width=\"60%\">\n";
echo "<input type=\"text\" name=\"bank_limit\" value=\"" . $setting->bank_limit . "\" />\n";
echo "</td></tr>\n";
echo "<tr><td colspan =\"2\">\n";
echo "This will limit the total amount that a player can place in their bank account.<br />";
echo "If 'Variable amount' was chosen as the limit type, this value will be multiplied with the player's level to give the limit.";
echo "</td></tr>\n";

echo "<tr><td width=\"40%\">\n";
echo "</td><td width=\"60%\">\n";
echo "<br /><input type=\"submit\" name=\"submit_form\" value=\"Submit\" />\n";
echo "</td></tr>\n";

echo "</table>\n";
echo "</form>\n";

include("./footer.php");
?>