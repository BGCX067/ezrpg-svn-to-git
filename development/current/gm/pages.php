<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Pages Config");

if (check_permissions($player, 85) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

include("./header.php");

echo "Here you can edit different pages of your game individually.<br />";
echo "Which page would you like to modify?<br /><br />\n";

echo "<ul>\n";
echo "<li><a href=\"conf_general.php\">General Config</a><br /></li>\n";
echo "<li><a href=\"conf_bank.php\">Bank (bank.php)</a></li>\n";
echo "<li><a href=\"conf_battle.php\">Battle (battle.php)</a></li>\n";
echo "<li><a href=\"conf_hospital.php\">Hospital (hospital.php)</a></li>\n";
echo "<li><a href=\"conf_index.php\">Index/Login (index.php)</a></li>\n";
//echo "<li><a href=\"conf_inventory.php\">Inventory (inventory.php)</a></li>\n"; //No options for these pages yet
//echo "<li><a href=\"conf_log.php\">Log (log.php)</a></li>\n";
//echo "<li><a href=\"conf_mail.php\">Mail (mail.php)</a></li>\n";
echo "<li><a href=\"conf_members.php\">Members (members.php)</a></li>\n";
//echo "<li><a href=\"conf_profile.php\">Profile (profile.php)</a></li>\n";
echo "<li><a href=\"conf_register.php\">Register (register.php)</a></li>\n";
//echo "<li><a href=\"conf_shop.php\">Shop (shop.php)</a></li>\n";
//echo "<li><a href=\"conf_stat_points.php\">Stat Points (stat_points.php)</a></li>\n";
echo "</ul>\n";

include("./footer.php");
?>