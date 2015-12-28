<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Edit Member");

if (check_permissions($player, 75) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

//List of fields that cannot be edited
$noedit[] = "id";
$noedit[] = "username";
$noedit[] = "password";
$noedit[] = "email";

$query = $db->execute("select * from `players` where `id`=?", array(intval($_GET['id'])));
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

if (isset($_POST['gm_rank']))
{
	$update = $_POST;
	
	$query = "update `players` set ";
	$i = 1;
	foreach($update as $k=>$v)
	{
		if ($k == 'gm_rank')
		{
			//Cannot change GM rank or $edit's gm rank is higher than the player's
			if (check_permissions($player, 80) == 0 || $edit['gm_rank']  > $player->gm_rank)
			{
				$update['gm_rank'] = $edit['gm_rank'];
			}
			else
			{
				$update['gm_rank'] = intval($_POST['gm_rank']);
			}
		}
		
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
	
	$query = $db->execute("select * from `players` where `id`=?", array($edit['id']));
	$edit = $query->fetchrow();
	
	$msg = "You have successfully updated this player!";
}

include("./header.php");

if ($msg)
{
	echo "<font color=\"red\">";
	echo $msg;
	echo "</font>\n";
	echo "<br /><br />\n";
}

echo "<b>Editing " . $edit['username'] . "</b><br /><br />\n";

echo "<form method=\"post\" action=\"edit_member.php?id=" . $edit['id'] . "\">\n";
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