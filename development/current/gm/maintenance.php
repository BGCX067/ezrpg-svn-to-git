<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Maintenance");

if (check_permissions($player, 60) == 0) //GM rank must be 80+ to perform maintenance
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

$result = "";
if ($_POST['type'])
{
	$sqlquery = "delete from `" . $_POST['type'] . "` where `time`<?";
	$values = array();
	switch($_POST['age'])
	{
		case "week":
			array_push($values, time()-(60*60*24*7));
			break;
		case "month":
			array_push($values, time()-(60*60*24*30));
			break;
	}
	switch($_POST['status'])
	{
		case "read":
			$sqlquery .= " and `status`=?";
			array_push($values, "read");
			break;
		case "unread":
			$sqlquery .= " and `status`=?";
			array_push($values, "unread");
			break;
	}
	$query = $db->execute($sqlquery, $values);
	if ($db->affected_rows() == 0)
	{
		$result = "\n\n<br /><br /><br />No entries were deleted.\n";
	}
	else
	{
		$result = "\n\n<br /><br /><br /><b>" . $db->affected_rows() . "</b> entries deleted.\n";
	}
}


include("./header.php");

echo "<u>Warning: Performing any of these maintenance actions will delete messages forever!</u>\n";
echo "<br /><br />\n";

echo "<table width=\"100%\">";
echo "<tr><td width=\"50%\">\n";

echo "<fieldset>\n";
echo "<legend><b>Clear Mail</b></legend>\n";
echo "<form method=\"post\" action=\"maintenance.php\">\n";
echo "<input type=\"hidden\" name=\"type\" value=\"mail\" />\n";
echo "Older than: <select name=\"age\">\n";
echo "<option value=\"week\" selected=\"selected\">One week</option>\n";
echo "<option value=\"month\">30 Days</option>\n";
echo "</select>\n<br />";
echo "Status: <select name=\"status\">\n";
echo "<option value=\"read\" selected=\"selected\">Read Mail</option>\n";
echo "<option value=\"unread\">Unread Mail</option>\n";
echo "<option value=\"all\">All Mail</option>\n";
echo "</select>\n<br />";
echo "<input type=\"submit\" value=\"Delete!\" />";
echo "</form>\n";

$query = $db->execute("select `id` from `mail`");
echo "<b>Total Entries:</b> " . $query->recordcount() . "\n";
echo "</fieldset>\n";

echo "</td><td width=\"50%\">\n";

echo "<fieldset>\n";
echo "<legend><b>Clear Logs</b></legend>\n";
echo "<form method=\"post\" action=\"maintenance.php\">\n";
echo "<input type=\"hidden\" name=\"type\" value=\"user_log\" />\n";
echo "Older than: <select name=\"age\">\n";
echo "<option value=\"week\" selected=\"selected\">One week</option>\n";
echo "<option value=\"month\">30 Days</option>\n";
echo "</select>\n<br />";
echo "Status: <select name=\"status\">\n";
echo "<option value=\"read\" selected=\"selected\">Read Logs</option>\n";
echo "<option value=\"unread\">Unread Logs</option>\n";
echo "<option value=\"all\">All Logs</option>\n";
echo "</select>\n<br />";
echo "<input type=\"submit\" value=\"Delete!\" />";
echo "</form>\n";

$query = $db->execute("select `id` from `user_log`");
echo "<b>Total Entries:</b> " . $query->recordcount() . "\n";
echo "</fieldset>\n";

echo "</td></tr>\n";
echo "</table>";

echo $result;

include("./footer.php");
?>