<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("lib.php");
define("PAGENAME", $lang['page_members']);
$player = check_user($secret_key, $db);

$limit = (!isset($_GET['limit']))?intval($setting->members_default_limit):intval($_GET['limit']); //Use default limit or user-selected limit

$page = (intval($_GET['page']) == 0)?1:intval($_GET['page']); //Start on page 1 or $_GET['page']

$begin = ($limit * $page) - $limit; //Starting point for query

$total_players = $db->getone("select count(ID) as `count` from `players`");


include("templates/private_header.php");

//*********************************//
//Start of pagination chunk, displaying page numbers and links

//Display 'Previous' link
echo ($page != 1)?"<a href=\"members.php?limit=" . $limit . "&page=" . ($page-1) . "\">" . $lang['keyword_previous'] . "</a> | ":$lang['keyword_previous'] . " | ";

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
echo (($total_players - ($limit * $page)) > 0)?"<a href=\"members.php?limit=" . $limit . "&page=" . ($page+1) . "\">" . $lang['keyword_next'] . "</a> ":$lang['keyword_next'];

//*********************************//
?>
<br /><br />
<b>
<?=$lang['keyword_show']?> <a href="members.php?begin=<?=$begin?>&limit=5">5</a> | <a href="members.php?begin=<?=$begin?>&limit=10">10</a>  | <a href="members.php?begin=<?=$begin?>&limit=20">20</a> | <a href="members.php?begin=<?=$begin?>&limit=30">30</a> | <a href="members.php?begin=<?=$begin?>&limit=40">40</a> | <a href="members.php?begin=<?=$begin?>&limit=50">50</a> | <a href="members.php?begin=<?=$begin?>&limit=100">100</a> <?=$lang['keyword_members']?>
</b>

<br /><br />

<?="<b>" . $lang['keyword_total'] . " " . $lang['keyword_members'] . "</b>: " . $total_players?>

<br /><br />

<table width="100%" border="0">
<tr>
<th width="30%"><b><?=$lang['keyword_username']?></b></td>
<th width="30%"><b><?=$lang['keyword_level']?></b></td>
<th width="40%"><b><?=$lang['keyword_actions']?></b></td>
</tr>
<?php
//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute("select `id`, `username`, `level` from `players` order by `level` desc limit ?,?", array($begin, $limit));

$bool = 1;
while($member = $query->fetchrow())
{
	echo "<tr class=\"row" . $bool . "\">\n";
	echo "<td><a href=\"profile.php?id=" . $member['username'] . "\">";
	echo ($member['username'] == $player->username)?"<b>":"";
	echo $member['username'];
	echo ($member['username'] == $player->username)?"</b>":"";
	echo "</a></td>\n";
	echo "<td>" . $member['level'] . "</td>\n";
	echo "<td><a href=\"mail.php?act=compose&to=" . $member['username'] . "\">" . $lang['keyword_mail'] . "</a> | <a href=\"battle.php?act=attack&username=" . $member['username'] . "\">" . $lang['keyword_attack'] . "</a></td>\n";
	echo "</tr>\n";
	$bool = ($bool==1)?2:1;
}
?>
</table>

<?php
include("templates/private_footer.php");
?>