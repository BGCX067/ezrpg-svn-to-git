<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Stats");
include("./header.php");


$query = $db->execute("select `id`, `gold`, `bank`, `level`, `kills`, `deaths` from `players`");
$numplayers = $query->recordcount(); //Total players
$totalgold = 0; //Total gold
$totalbattles = 0; //Total battles
$totallevel = 0; //Total of all player levels
while ($row = $query->fetchrow())
{
	$totalgold += $row['gold'] + $row['bank']; //Add to total gold
	$totalbattles += $row['kills'] + $row['deaths']; //Add to total battles
	$totallevel += $row['level']; //Add to total level
}
$averagegold = intval($totalgold / $numplayers);
$averagelevel = intval($totallevel / $numplayers);

$query2 = $db->execute("select max(`gold`) as `gold`, max(`level`) as `level`, max(`bank`) as `bank` from `players`");
$highest = $query2->fetchrow();

echo "<table width=\"100%\">\n";
echo "<tr><td width=\"50%\">\n";

echo "<fieldset><legend><b>Player Stats</b></legend>\n";
echo "<b>Total Players:</b> " . $numplayers . "<br />\n";
echo "<b>Highest Level:</b> " . $highest['level'] . "<br />\n";
echo "<b>Average Level:</b> " . $averagelevel . "<br />\n";
echo "</fieldset>\n";

echo "</td><td width=\"50%\">\n";



echo "<fieldset><legend><b>Economy Stats</b></legend>\n";
echo "<b>Total Gold:</b> " . $totalgold . "<br />\n";
echo "<b>Average Gold:</b> " . $averagegold . "<br />\n";
echo "<b>Richest (Gold):</b> " . $highest['gold'] . "<br />\n";
echo "<b>Richest (Bank):</b> " . $highest['bank'] . "<br />\n";
echo "</fieldset>\n";

echo "</td></tr>\n";

echo "<tr><td width=\"50%\">\n";

echo "<fieldset><legend><b>Log Stats</b></legend>\n";
$log_query = $db->execute("select count(`id`) as `count` from `user_log` where `status`='unread'");
$logs = $log_query->fetchrow();
$log_query = $db->execute("select count(`id`) as `count` from `user_log` where `status`='read'");
$logs2 = $log_query->fetchrow();
echo "<b>User Logs (Total):</b> " . ($logs['count'] + $logs2['count']) . "<br />\n";
echo "<b>User Logs (Unread):</b> " . $logs['count'] . "<br />\n";
echo "<b>User Logs (Read):</b> " . $logs2['count'] . "<br />\n";
echo "</fieldset>\n";

echo "</td><td width=\"50%\">\n";

echo "<fieldset><legend><b>Mail Stats</b></legend>\n";
$mail_query = $db->execute("select count(`id`) as `count` from `mail` where `status`='unread'");
$mails = $mail_query->fetchrow();
$mail_query = $db->execute("select count(`id`) as `count` from `mail` where `status`='read'");
$mails2 = $mail_query->fetchrow();
echo "<b>Mail (Total):</b> " . ($mails['count'] + $mails2['count']) . "<br />\n";
echo "<b>Mail (Unread):</b> " . $mails['count'] . "<br />\n";
echo "<b>Mail (Read):</b> " . $mails2['count'] . "<br />\n";
echo "</fieldset>\n";

echo "</td></tr>\n";
echo "</table>\n";

include("./footer.php");
?>