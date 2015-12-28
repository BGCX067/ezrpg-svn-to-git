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

if (!$_GET['id'] || !$_GET['days']) //No member id to ban
		{
				header("Location: ban_member.php");
			
		} 

else 
		{		
				include("./header.php");
				$bantime = time() + ($_GET['days'] * 86400);
				$query = $db->execute("update `players` set `ban`=?  where `id`=?", array($bantime, $_GET['id']));
				echo "The member has been banned for " . $_GET['days'] . " days";
				echo "<div align='right'><INPUT TYPE=\"BUTTON\" VALUE=\"Home\" ONCLICK=\"window.location.href='index.php'\"></div>";
				include("./footer.php");
		}