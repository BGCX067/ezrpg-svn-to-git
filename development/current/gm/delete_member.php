<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*         and CheapDevotion         */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Delete Member");

if (check_permissions($player, 75) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

if ($_GET['act'])
{
switch($_GET['act'])
{
	case "delete":
		if (!$_GET['id']) //No item ID
		{
			header("Location: index.php");
			break;
		}
				include("./header.php");
				$query = $db->execute("delete from `players` where `id`=?", array($_GET['id']));
				echo "The member has been deleted from the database.";
				echo "<div align='right'><INPUT TYPE=\"BUTTON\" VALUE=\"Home\" ONCLICK=\"window.location.href='index.php'\"></div>";
				include("./footer.php");
				break;
}
}
else {
//Select the item from the database
		$query = $db->execute("select * from `players` where `id`=?", array($_GET['id']));
		include("./header.php");
		
		//Invalid item (it doesn't exist)
		if ($query->recordcount() == 0)
		{
			echo "Item does not exist!";
		}
			
		else
		{
			echo "<b><i>Are you sure you want to delete this member?</b></i>";
			$profile = $query->fetchrow();
			{ ?>
				<fieldset>
			<legend><?=$profile['username']?>'s <?=$lang['keyword_profile']?></legend>
			<table width="90%">
			<tr>
			<td width="50%"><?=$lang['keyword_username']?>:</td>
			<td width="50%"><b><?=$profile['username']?> </b></td>
			</tr>
			<tr>
			<td><?=$lang['keyword_level']?>:</td>
			<td><?=$profile['level']?></td>
			</tr>
			<tr><td></td></tr>
			<tr>
			<td><?=$lang['keyword_status']?>:</td>
			<td><font color="<?=($profile['hp']==0)?"red\">Dead":"green\">Alive"?></font></td>
			</tr>
			<tr>
			<td><?=$lang['keyword_kills']?>:</td>
			<td><?=$profile['kills']?></td>
			</tr>
			<tr>
			<td><?=$lang['keyword_deaths']?>:</td>
			<td><?=$profile['deaths']?></td>
			</tr>
			<tr><td></td></tr>
			<tr>
			<td><?=$lang['keyword_registered']?>:</td>
			<td><?=date("F j, Y, g:i a", $profile['registered'])?></td>
			</tr>
			<tr>
			<td><?=$lang['keyword_age']?>:</td>
			<?php
			$diff = time() - $profile['registered'];
			$age = intval(($diff / 3600) / 24);
			?>
			<td><?=$age?> <?=$lang['keyword_days']?></td>
			</tr>
			</table>
			<br /><br />
			<center>
			</center>
			</fieldset>
			<?
			echo "<div align='right'>";
			echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Delete\" ONCLICK=\"window.location.href='delete_member.php?act=delete&id=" . $profile['id'] ."'\"> ";
			echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Back\" ONCLICK=\"history.back()\">";
			echo "</div>";
			}
		}
		include("./footer.php");
}