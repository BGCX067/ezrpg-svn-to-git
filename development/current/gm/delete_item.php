<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*         and CheapDevotion         */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Delete Item");

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
				$query = $db->execute("delete from `blueprint_items` where `id`=?", array($_GET['id']));
				echo "The item has been deleted from the database.";
				echo "<div align='right'><INPUT TYPE=\"BUTTON\" VALUE=\"Home\" ONCLICK=\"window.location.href='index.php'\"></div>";
				include("./footer.php");
				break;
}
}
else {
//Select the item from the database
		$query = $db->execute("select * from `blueprint_items` where `id`=?", array($_GET['id']));
		include("./header.php");
		
		//Invalid item (it doesn't exist)
		if ($query->recordcount() == 0)
		{
			echo "Item does not exist!";
		}
			
		else
		{
			echo "<b><i>Are you sure you want to delete this item?</b></i>";
			while ($item = $query->fetchrow())
			{
				echo "<br />";
				echo "<fieldset>\n";
				echo "<legend><b>" . $item['name'] . "</b></legend>\n";
				echo "<table width=\"100%\">\n";
				echo "<tr><td width=\"85%\">";
				echo $item['description'] . "\n<br />";
				echo "<b>Effectiveness:</b> " . $item['effectiveness'] . "\n";
				echo "</td><td width=\"15%\">";
				echo "<b>Price:</b> " . $item['price'] . "<br />";
				echo "</td></tr>\n";
				echo "</table>";
				echo "</fieldset>\n<br />";
				echo "<div align='right'>";
				echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Delete\" ONCLICK=\"window.location.href='delete_item.php?act=delete&id=" . $item['id'] ."'\"> ";
				echo "<INPUT TYPE=\"BUTTON\" VALUE=\"Back\" ONCLICK=\"history.back()\">";
				echo "</div>";
			}
		}
		include("./footer.php");
}