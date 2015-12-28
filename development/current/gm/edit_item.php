<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*         and CheapDevotion         */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Edit Items");

if (check_permissions($player, 75) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

//List of fields that cannot be edited
$noedit[] = "id";

//List of field that require a textarea to edit
$textarea[] = "description";

$query = $db->execute("select * from `blueprint_items` where `id`=?", array(intval($_GET['id'])));
if ($query->recordcount() == 0)
{
	include("./header.php");
	echo "This player does not exist!";
	include("./footer.php");
	exit;
}
else if ($_GET['id'] == $player->id && $player->gm_rank < 80)
{
	include("./header.php");
	echo "Your GM level is not high enough to edit yourself!";
	include("./footer.php");
	exit;
}
else
{
	$edit = $query->fetchrow();
}
// This is just a quick hack for speed, at least one of the fields has to be changed to update the database
if (isset($_POST['name']) || isset($_POST['description']) || isset($_POST['type']) || isset($_POST['effectiveness']) || isset($_POST['price']))
{
	$update = $_POST;
	
	$query = "update `blueprint_items` set ";
	$i = 1;
	foreach($update as $k=>$v)
	{
			
		//If cannot be edited, do not add to query
		if (!in_array($k, $noedit))
		{
			$query .= "`" . $k . "`=?";
			
			//If not last element, add comma to query
			if ($i != count($update))
			{
				$query .= ", ";
			}
		}
		$values[] = $v;
		$i++;
	}
	$query .= " where `id`=?";
	$values[] = $edit['id'];
	
	$query = $db->execute($query, $values);
	
	$query = $db->execute("select * from `blueprint_items` where `id`=?", array($edit['id']));
	$edit = $query->fetchrow();
	
	$msg = "You have successfully updated this item!";
}

include("./header.php");

if ($msg)
{
	echo "<font color=\"red\">";
	echo $msg;
	echo "</font>\n";
	echo "<br /><br />\n";
}

echo "<b>Editing " . $edit['name'] . "</b><br /><br />\n";

echo "<form method=\"post\" action=\"edit_item.php?id=" . $edit['id'] . "\">\n";
echo "<table width=\"70%\">\n";
foreach($edit as $k=>$v)
{
	echo "<tr>\n";
	echo "<td width=\"35%\">";
	
	//If field cannot be edited, show field name in red
	if (in_array($k, $noedit))
	{
		echo "<font color=\"red\">";
	}
	
	echo ucwords(strtolower($k));
	
	if (in_array($k, $noedit))
	{
		echo "</font>";
	}
	
	echo "</td>\n";
	echo "<td width=\"65%\">";
	
	//Add readonly property to input field if field cannot be edited
	if (in_array($k, $noedit))
	{
		echo "<input type=\"text\" value=\"" . $v . "\" readonly=\"readonly\" />";
	}
	// Add textareas for fields that need them
	elseif (in_array($k, $textarea))
	{
		echo "<textarea cols=\"30\" rows=\"5\" name=\"" . $k ."\">" .$v ."</textarea>";
	}
	
	else
	{
		echo "<input type=\"text\" name=\"" . $k . "\" value=\"" . $v . "\"/>";
	}
	
	echo "</td>\n";
	echo "</tr>\n";
}
echo "<tr><td></td><td><input type=\"submit\" value=\"Edit\" /></td></tr>\n";
echo "</table>\n";
echo "</form>\n";


include("./footer.php");
?>