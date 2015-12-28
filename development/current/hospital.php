<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("lib.php");
define("PAGENAME", $lang['page_hospital']);
$player = check_user($secret_key, $db);

if ($player->hp == $player->maxhp)
{
	include("templates/private_header.php");
	echo "<b>" . $lang['npc_hospital'] . ":</b><br />\n";
	echo "<i>" . $lang['msg_full_health'] . "</i>\n";
	include("templates/private_footer.php");
	exit;
}
else
{
	//Add possibility of PARTIAL healing in next version?
	
	$heal = $player->maxhp - $player->hp;
	$cost = $heal * $setting->hospital_rate; //hospital_rate is cost of healing 1 HP
	
	if ($_GET['act'])
	{
		if ($player->gold < $cost)
		{
			include("templates/private_header.php");
			echo "<b>" . $lang['npc_hospital'] . ":</b><br />\n";
			echo "<i><font color=\"red\">" . $lang['error_gold'] . "</font></i>\n";
			include("templates/private_footer.php");
			exit;
		}
		else
		{
			$query = $db->execute("update `players` set `gold`=?, `hp`=? where `id`=?", array($player->gold - $cost, $player->maxhp, $player->id));
			$player = check_user($secret_key, $db); //Get new stats
			include("templates/private_header.php");
			echo "<b>" . $lang['npc_hospital'] . ":</b><br />\n";
			echo "<i>" . $lang['msg_healed'] . "</i>\n";
			include("templates/private_footer.php");
			exit;
		}
	}
	
	include("templates/private_header.php");
?>
<b><?=$lang['npc_hospital']?>:</b><br />
<i><?=sprintf($lang['msg_hospital'], $cost)?></i>
<br />
<a href="hospital.php?act=heal"><?=$lang['keyword_heal']?>!</a>
<?php
	include("templates/private_footer.php");
	exit;
}
?>