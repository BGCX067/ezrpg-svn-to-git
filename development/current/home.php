<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("lib.php");
define("PAGENAME", $lang['page_home']);
$player = check_user($secret_key, $db);

include("templates/private_header.php");
?>

<table width="100%" border="0">
<tr>
<td width="50%">
<b><?=$lang['keyword_username']?>:</b> <?=$player->username?><br />
<b><?=$lang['keyword_email']?>:</b> <?=$player->email?><br />
<b><?=$lang['keyword_registered']?>:</b> <?=date("F j, Y, g:i a", $player->registered)?><br />
<?php
$diff = time() - $player->registered;
$age = intval(($diff / 3600) / 24);
?>
<b><?=$lang['keyword_age']?>:</b> <?=$age?> <?=$lang['keyword_days']?><br />
<b><?=$lang['keyword_kills']?>/<?=$lang['keyword_deaths']?>:</b> <?=$player->kills?>/<?=$player->deaths?><br />
<br />
<?php
if ($player->stat_points > 0)
{
	echo sprintf($lang['msg_spend_statpoints'], $player->stat_points);
}
else
{
	echo $lang['error_no_statpoints'];
}
?>
</td>
<td width="50%">
<b><?=$lang['keyword_level']?>:</b> <?=$player->level?><br />
<?php
$percent = intval(($player->exp / $player->maxexp) * 100);
?>
<b><?=$lang['keyword_exp']?>:</b> <?=$player->exp?>/<?=$player->maxexp?> (<?=$percent?>%)<br />
<b><?=$lang['keyword_hp']?>:</b> <?=$player->hp?>/<?=$player->maxhp?><br />
<b><?=$lang['keyword_energy']?>:</b> <?=$player->energy?>/<?=$player->maxenergy?><br />
<b><?=$lang['keyword_gold']?>:</b><?=$player->gold?><br />
<br />
<b><?=$lang['keyword_strength']?>:</b> <?=$player->strength?><br />
<b><?=$lang['keyword_vitality']?>:</b> <?=$player->vitality?><br />
<b><?=$lang['keyword_agility']?>:</b><?=$player->agility?><br />
</td>
</tr>
</table>


<br /><br />

<!-- Here you can put news updates or whatever. A News System will be coming next version! -->

<br /><br />
<center><a href="http://ezrpg.bbgamezone.com/">ezRPG Project</a></center>
<!-- Yes, you may remove that link if you wish :) -->

<?php
include("templates/private_footer.php");
?>