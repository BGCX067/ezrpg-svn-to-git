<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*         and CheapDevotion         */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Weapon Administration");

if (check_permissions($player, 75) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

$limit = (!isset($_GET['limit']))?intval($setting->weapons_default_limit):intval($_GET['limit']); //Use default limit or user-selected limit

$page = (intval($_GET['page']) == 0)?1:intval($_GET['page']); //Start on page 1 or $_GET['page']

$begin = ($limit * $page) - $limit; //Starting point for query

$total_weapons = $db->getone("select count(ID) as `count` from `blueprint_items` where type = 'weapon'");


include("./header.php");

//*********************************//
//Start of pagination chunk, displaying page numbers and links

//Display 'Previous' link
echo ($page != 1)?"<a href=\"weapons.php?limit=" . $limit . "&page=" . ($page-1) . "\">Previous</a> | ":"Previous | ";

$numpages = $total_weapons / $limit;
for ($i = 1; $i <= $numpages; $i++)
{
	//Display page numbers
	echo ($i == $page)?$i . " | ":"<a href=\"weapons.php?limit=" . $limit . "&page=" . $i . "\">" . $i . "</a> | ";
}

if (($total_weapons % $limit) != 0)
{
	//Display last page number if there are left-over users in the query
	echo ($i == $page)?$i . " | ":"<a href=\"weapons.php?limit=" . $limit . "&page=" . $i . "\">" . $i . "</a> | ";
}

//Display the 'Next' link
echo (($total_weapons - ($limit * $page)) > 0)?"<a href=\"weapons.php?limit=" . $limit . "&page=" . ($page+1) . "\">Next</a> ":"Next";

//*********************************//
?>
<br /><br />
<b>
Show <a href="weapons.php?begin=<?=$begin?>&limit=5">5</a> | <a href="weapons.php?begin=<?=$begin?>&limit=10">10</a>  | <a href="weapons.php?begin=<?=$begin?>&limit=20">20</a> | <a href="weapons.php?begin=<?=$begin?>&limit=30">30</a> | <a href="weapons.php?begin=<?=$begin?>&limit=40">40</a> | <a href="weapons.php?begin=<?=$begin?>&limit=50">50</a> | <a href="weapons.php?begin=<?=$begin?>&limit=100">100</a> Weapons
</b>

<br /><br />

<?="<b>Total Weapons</b>: " . $total_weapons?>

<br />

Weapons are sorted alphabetically.

<br /><br />

<table width="100%" border="0">
<tr>
<th width="30%"><b>Name</b></td>
<th width="10%"><b>Effectiveness</b></td>
<th width="10%"><b>Price</b></td>
<th width="40%"><b>Actions</b></td>
</tr>
<?php
//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute("select `id`, `name`, `effectiveness`, `price` from `blueprint_items` where type = 'weapon' order by `name` asc limit ?,?", array($begin, $limit));

$bool = 1;
while($weapon = $query->fetchrow())
{
	echo "<tr class=\"row" . $bool . "\">\n";
	echo "<td>" . $weapon['name'] . "</td>\n";
	echo "<td>" . $weapon['effectiveness'] . "</td>\n";
	echo "<td>" . $weapon['price'] . "</td>\n";
	echo "<td><a href=\"edit_item.php?id=" . $weapon['id'] . "\">Edit</a> | <a href=\"delete_item.php?id=" . $weapon['id'] . "\">Delete</a></td>\n";
	echo "</tr>\n";
	$bool = ($bool==1)?2:1;
}
?>
</table>
<br>
<br>
<div align="right"><INPUT TYPE="BUTTON" VALUE="Add Item" ONCLICK="window.location.href='add_item.php'"></div>

<?php
include("./footer.php");
?>