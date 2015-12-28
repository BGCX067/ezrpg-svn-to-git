<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "GM Panel");

include("./header.php");
echo "<table width=\"100%\">\n";
echo "<tr><td width=\"50%\">\n";
echo "<fieldset>\n<legend><b>Latest Version</b></legend>\n";
$latest_version = file_get_contents("http://ezrpg.bbgamezone.com/latest_version.txt");
if (!$latest_version)
{
	echo "<iframe src=\"http://ezrpg.bbgamezone.com/latest_version.php?v=" . $version . "\" frameborder=\"0\" height=\"60\" width=\"100%\" />Checking for latest version...</iframe>";
}
else
{
	echo "Latest version: <b>" . $latest_version . "</b><br />";
	echo "Your version: <b>" . $version . "</b><br />";
	echo ($latest_version == $version)?"<font color=\"green\">Your ezRPG is up to date!</font>":"<font color=\"red\">Your ezRPG is outdated, please upgrade!</font>";
}
echo "</fieldset>\n";
echo "</td><td width=\"50%\">\n";
echo "<fieldset>\n<legend><b>ezRPG</b></legend>\n";
echo "<ul>\n";
echo "<li><a href=\"http://ezrpg.bbgamezone.com\">ezRPG Homepage</a></li>\n";
echo "<li><a href=\"http://ezrpg.bbgamezone.com/forum\">Support Forum</a></li>\n";
echo "</ul>\n";
echo "</fieldset>\n";
echo "</td></tr>\n";

echo "<tr><td colspan=\"2\">\n";
echo "<fieldset>\n<legend><b>Quick Stats</b></legend>\n";

echo "<table width=\"100%\">\n";
echo "<tr><td width=\"50%\">\n";
$query = $db->execute("select `id`, `gold`, `bank`, `level`, `kills`, `deaths` from `players`");
$numplayers = $query->recordcount();
echo "<b><a href=\"members.php\">Total Players</a>:</b> " . $numplayers . "<br />\n";

$totalgold = 0;
$highestlevel = 0;
$totalbattles = 0;
while ($row = $query->fetchrow())
{
	$totalgold += $row['gold'] + $row['bank'];
	if ($row['level'] > $highestlevel)
	{
		$highestlevel = $row['level'];
	}
	$totalbattles += $row['kills'] + $row['deaths'];
}
$averagegold = intval($totalgold / $numplayers);
echo "<b>Total Gold:</b> " . $totalgold . "<br />\n";
echo "<b>Highest Level:</b> " . $highestlevel . "<br />\n";
$query2 = $db->execute("select `id` from `items`");
$numitems = $query2->recordcount();
echo "<b>Total Items:</b> " . $numitems . "<br />\n";
echo "<b>Total Battles:</b> " . intval(($totalbattles) / 2) . "<br />\n";

echo "</td><td width=\"50%\">\n";
echo "<u>New Players</u><br />\n";
$query = $db->execute("select `id` from `players` where `registered`>?", array((time() - (60*60*24))));
$newplayers = $query->recordcount();
echo "<b>Last 24 hours:</b> " . $newplayers . "<br />\n";

echo "<br />\n";

echo "<u>Active Players</u><br />\n";
$query = $db->execute("select `id` from `players` where `last_active`>?", array((time() - (60*60*24))));
$activeplayers = $query->recordcount();
echo "<b>Last 24 hours:</b> " . $activeplayers . "<br />\n";
echo "</td></tr>\n";
echo "</table>\n";

echo "</fieldset>\n";
echo "</td></tr>\n";

echo "<tr><td>\n";
echo "<fieldset>\n";
echo "<legend><b>Latest GM Log</b></legend>\n";
$query = $db->execute("select `msg` from `log_gm` order by `id` desc limit 1");
$logcount = $query->recordcount();
if ($logcount == 0)
{
	echo "You have no GM log messages.";
}
else
{
	$msg = $query->fetchrow(); //The log message
	$position = 100; //Minimum # of characters to display
	if (strlen($msg['msg']) <= $position)
	{
		echo "<i>" . $msg['msg'] . "</i>";
	}
	else
	{
		$post = substr($msg['msg'],$position,1); //Get the last character
		while($post != " ") { //If last character is part of a word
			$position++;
			$post = substr($msg['msg'],$position,1);
		}
		echo "<i>" . substr($msg['msg'],0,$position) . "...</i>";
	}
	
}
echo "</fieldset>\n";
echo "</td><td>\n";

echo "<fieldset>\n";
echo "<legend><b>Latest Error Log</b></legend>\n";
$query = $db->execute("select `msg` from `log_errors` order by `id` desc limit 1");
$logcount = $query->recordcount();
if ($logcount == 0)
{
	echo "You have no error log messages.";
}
else
{
	$msg = $query->fetchrow(); //The log message
	$position = 100; //Minimum # of characters to display
	if (strlen($msg['msg']) <= $position)
	{
		echo "<i>" . $msg['msg'] . "</i>";
	}
	else
	{
		$post = substr($msg['msg'],$position,1); //Get the last character
		while($post != " ") { //If last character is part of a word
			$position++;
			$post = substr($msg['msg'],$position,1);
		}
		echo "<i>" . substr($msg['msg'],0,$position) . "...</i>";
	}
}
echo "</fieldset>\n";

echo "</td></tr>\n";

echo "<tr><td colspan=\"2\">\n";
echo "<fieldset>\n";
echo "<legend>Latest News:</legend>\n";
echo "<iframe src=\"http://ezrpg.bbgamezone.com/latest_news.php\" frameborder=\"0\" height=\"160\" width=\"100%\" />Checking for latest news...</iframe>";
echo "</fieldset>\n";
echo "</td></tr>\n";

//TODO: Add pagination to GM Log, Error Log, User Log (maybe change user log to be similar to inbox)
//TODO: Add 'outbox' in mail.php

echo "</table>\n";

include("./footer.php");
?>