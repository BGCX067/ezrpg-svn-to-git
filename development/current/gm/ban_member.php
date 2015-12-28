<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*         and CheapDevotion         */
/*    http://www.ezrpgproject.com    */
/*************************************/
include("./lib.php");
define("PAGENAME", "Member List");

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
	case "unban":
		if (!$_GET['id']) //No item ID
		{
			header("Location: ban_member.php");
			break;
		}
				include("./header.php");
				$query = $db->execute("update `players` set `ban`= 0 where `id`=?", array($_GET['id']));
				echo "The member has been unbanned.";
				echo "<div align='right'><INPUT TYPE=\"BUTTON\" VALUE=\"Home\" ONCLICK=\"window.location.href='index.php'\"></div>";
				include("./footer.php");
				break;

	case "ban":
		if (!$_GET['id']) //No item ID
		{
			header("Location: index.php");
			break;
		}
				include("./header.php");
				
					?>
					
					<table width="100%" border="0">
					<tr>
					<td width="40%">								
					<i>Please select the amount of days to ban the member.</i>
					</tr>
					<tr>
					<td width="40%">
					<form method="GET" action="ban.php">
					Days: <input type="text" name="days" value="" /><br />
					<input type="hidden" name="id" value="<?=$_GET['id']?>" /><br />
					<input type="submit" value="Ban" />
					</form>
					</td>
					</tr>
					</table>
					
					<?php
					include("./footer.php");
					
				break;
}



}
else {



$limit = (!isset($_GET['limit']))?intval($setting->members_default_limit):intval($_GET['limit']); //Use default limit or user-selected limit

$page = (intval($_GET['page']) == 0)?1:intval($_GET['page']); //Start on page 1 or $_GET['page']

$begin = ($limit * $page) - $limit; //Starting point for query

$total_players = $db->getone("select count(ID) as `count` from `players` where `ban`>=" . time());


include("./header.php");

//*********************************//
//Start of pagination chunk, displaying page numbers and links

//Display 'Previous' link
echo ($page != 1)?"<a href=\"ban_member.php?limit=" . $limit . "&page=" . ($page-1) . "\">Previous</a> | ":"Previous | ";

$numpages = $total_players / $limit;
for ($i = 1; $i <= $numpages; $i++)
{
	//Display page numbers
	echo ($i == $page)?$i . " | ":"<a href=\"ban_member.php?limit=" . $limit . "&page=" . $i . "\">" . $i . "</a> | ";
}

if (($total_players % $limit) != 0)
{
	//Display last page number if there are left-over users in the query
	echo ($i == $page)?$i . " | ":"<a href=\"ban_member.php?limit=" . $limit . "&page=" . $i . "\">" . $i . "</a> | ";
}

//Display the 'Next' link
echo (($total_players - ($limit * $page)) > 0)?"<a href=\"ban_member.php?limit=" . $limit . "&page=" . ($page+1) . "\">Next</a> ":"Next";

//*********************************//
?>
<br /><br />
<b>
Show <a href="ban_member.php?begin=<?=$begin?>&limit=5">5</a> | <a href="ban_member.php?begin=<?=$begin?>&limit=10">10</a>  | <a href="ban_member.php?begin=<?=$begin?>&limit=20">20</a> | <a href="ban_member.php?begin=<?=$begin?>&limit=30">30</a> | <a href="ban_member.php?begin=<?=$begin?>&limit=40">40</a> | <a href="ban_member.php?begin=<?=$begin?>&limit=50">50</a> | <a href="ban_member.php?begin=<?=$begin?>&limit=100">100</a> Members
</b>

<br /><br />

<?="<b>Total Members Banned</b>: " . $total_players?>

<br />

Players are ordered by their username. <br>

<br /><br />

<table width="100%" border="0">
<tr>
<th width="20%"><b>Username</b></td>
<th width="30%"><b>Email</b></td>
<th width="20%"><b>IP Address </b></td>
<th width="10%"><b>Days </b></td>
<th width="30%"><b>Actions</b></td>
</tr>
<?php
//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute("select * from `players` where `ban`>=?", array(time()));

$bool = 1;
while($member = $query->fetchrow())
{
	
	$time = $member['ban'] - time();
	$days_remaining = $time / 86400;
	echo "<tr class=\"row" . $bool . "\">\n";
	echo "<td><a href=\"../profile.php?id=" . $member['username'] . "\">";
	echo ($member['username'] == $player->username)?"<b>":"";
	echo $member['username'];
	echo ($member['username'] == $player->username)?"</b>":"";
	echo "</a></td>\n";
	echo "<td>" . $member['email'] . "</td>\n";
	echo "</a></td>\n";
	echo "<td>" . $member['ip'] . "</td>\n";
	echo "</a></td>\n";
	echo "<td>" . round($days_remaining) . "</td>\n";
	echo "<td><a href=\"ban_member.php?act=unban&id=" . $member['id'] . "\">Unban</a>";
	echo "</tr>\n";
	$bool = ($bool==1)?2:1;
}
?>
</table>

<?php
include("./footer.php");

}
?>