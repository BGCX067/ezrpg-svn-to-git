<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
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

$limit = (!isset($_GET['limit']))?intval($setting->members_default_limit):intval($_GET['limit']); //Use default limit or user-selected limit

$page = (intval($_GET['page']) == 0)?1:intval($_GET['page']); //Start on page 1 or $_GET['page']

$begin = ($limit * $page) - $limit; //Starting point for query

$total_players = $db->getone("select count(ID) as `count` from `players`");


include("./header.php");

//*********************************//
//Start of pagination chunk, displaying page numbers and links

//Display 'Previous' link
echo ($page != 1)?"<a href=\"members.php?limit=" . $limit . "&page=" . ($page-1) . "\">Previous</a> | ":"Previous | ";

$numpages = $total_players / $limit;
for ($i = 1; $i <= $numpages; $i++)
{
	//Display page numbers
	echo ($i == $page)?$i . " | ":"<a href=\"members.php?limit=" . $limit . "&page=" . $i . "\">" . $i . "</a> | ";
}

if (($total_players % $limit) != 0)
{
	//Display last page number if there are left-over users in the query
	echo ($i == $page)?$i . " | ":"<a href=\"members.php?limit=" . $limit . "&page=" . $i . "\">" . $i . "</a> | ";
}

//Display the 'Next' link
echo (($total_players - ($limit * $page)) > 0)?"<a href=\"members.php?limit=" . $limit . "&page=" . ($page+1) . "\">Next</a> ":"Next";

//*********************************//
?>
<br /><br />
<b>
Show <a href="members.php?begin=<?=$begin?>&limit=5">5</a> | <a href="members.php?begin=<?=$begin?>&limit=10">10</a>  | <a href="members.php?begin=<?=$begin?>&limit=20">20</a> | <a href="members.php?begin=<?=$begin?>&limit=30">30</a> | <a href="members.php?begin=<?=$begin?>&limit=40">40</a> | <a href="members.php?begin=<?=$begin?>&limit=50">50</a> | <a href="members.php?begin=<?=$begin?>&limit=100">100</a> Members
</b>

<br /><br />

<?="<b>Total Members</b>: " . $total_players?>

<br />

Players are ordered by their username.

<br /><br />

<table width="100%" border="0">
<tr>
<th width="30%"><b>Username</b></td>
<th width="30%"><b>Level</b></td>
<th width="40%"><b>Actions</b></td>
</tr>
<?php
//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute("select `id`, `username`, `level`, `ban` from `players` order by `username` asc limit ?,?", array($begin, $limit));

$bool = 1;
while($member = $query->fetchrow())
{
	echo "<tr class=\"row" . $bool . "\">\n";
	echo "<td><a href=\"../profile.php?id=" . $member['username'] . "\">";
	echo ($member['username'] == $player->username)?"<b>":"";
	echo $member['username'];
	echo ($member['username'] == $player->username)?"</b>":"";
	echo "</a></td>\n";
	echo "<td>" . $member['level'] . "</td>\n";
	echo "<td><a href=\"../mail.php?act=compose&to=" . $member['username'] . "\">Mail</a> | <a href=\"edit_member.php?id=" . $member['id'] . "\">Edit</a> |";
	if ($member['ban'] < time())
	{
		echo "<a href=\"ban_member.php?act=ban&id=" . $member['id'] . "\">Ban</a> |";
	}
	else 
	{
		echo "<a href=\"ban_member.php?act=unban&id=" . $member['id'] . "\">Unban</a> |";
	}
	echo " <a href=\"delete_member.php?id=" . $member['id'] . "\">Delete</a></td>\n";
	echo "</tr>\n";
	$bool = ($bool==1)?2:1;
}
?>
</table>

<?php
include("./footer.php");
?>