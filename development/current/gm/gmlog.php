<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "GM Log");

if ($_GET['delete'])
{
	$query = $db->execute("delete from `log_gm` where `id`=?", array($_GET['delete']));
}

include("./header.php");

$query = $db->execute("select `id`, `msg`, `time` from `log_gm` order by `id` desc");

while ($logmsg = $query->fetchrow())
{
	echo "<fieldset>\n";
	echo "<legend><b>" . date("F j, Y, g:i a", $logmsg['time']) . "</b> - <a href=\"gmlog.php?delete=" . $logmsg['id'] . "\">Delete</a></legend>\n";
	echo $logmsg['msg'] . "\n";
	echo "</fieldset><br />\n";
}

if ($query->recordcount() == 0)
{
	echo "No GM log messages!";
}

include("./footer.php");
?>