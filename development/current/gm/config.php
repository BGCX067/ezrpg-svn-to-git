<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Game Config");

if (check_permissions($player, 90) == 0) //GM rank must be 90+ to edit config.php
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

clearstatcache();
$perms = substr(sprintf('%o', fileperms('../config.php')), -4);

$noedit = "";

switch($_GET['action'])
{
	case "update":
		if (!is_writable("../config.php"))
		{
			header("Location: config.php");
			break;
		}
		else if ($_POST['config_password'] != $config_password)
		{
			$logmsg = "<a href=\"users.php?id=" . $player->username . "\">" . $player->username . "</a> attempted to change the game configuration file, but couldn't. (IP " . $ip . ")";
			gmlog($logmsg, $db);
			header("Location: config.php");
			exit;
		}
		else
		{
			copy("../config.php", "../config.php.bak"); //Create a backup of the current config file
			
			
			$newconfig = "<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://ezrpg.bbgamezone.com    */
/*************************************/

\$config_server = \"" . $_POST['config_server'] . "\"; //Database host
\$config_database = \"" . $_POST['config_database'] . "\"; //Database name
\$config_username = \"" . $_POST['config_username'] . "\"; //Database username
\$config_password = \"" . $_POST['config_password'] . "\"; //Database password
\$secret_key = \"" . $_POST['secret_key'] . "\"; //Secret key, make it a random word/sentence/whatever

\$config_language = \"" . $_POST['config_language'] . "\"; //Language of the game
\$config_name = \"" . $_POST['config_name'] . "\"; //Name of your game

//Do not edit below this line

\$version = \"" . $version . "\";";
$newconfig .= "?";
$newconfig .= ">";
			
			if (!$handle = fopen("../config.php", 'w'))
			{
				include("./header.php");
				echo "Cannot open config.php.<br />\nPlease check the file permissions are 0755.";
				include("./footer.php");
				break;
			}
			fwrite($handle, $newconfig);
			fclose($handle);
			
			$logmsg = "<a href=\"users.php?id=" . $player->username . "\">" . $player->username . "</a> changed the game configuration file. (IP " . $ip . ")";
			gmlog($logmsg, $db);
			
			header("Location: config.php");
			break;
		}
		break;
	default:
		include("./header.php");
		if (!is_writable("../config.php"))
		{
			$noedit = " disabled=\"disabled\"";
			echo "<b>config.php can't be edited!</b><br />\n";
			echo "Please change the file permissions to 0755. They are currently " . $perms . ".<br />\n";
			echo "This can be done from your FTP client, or from within your file manager.<br />\n";
			echo "<br /><hr width=\"80%\" /><br />";
			
		}
		echo "Here you can edit your game configuration settings, but it is recommended to leave them alone!<br /><br />\n";

		echo "<form method=\"post\" action=\"config.php?action=update\">\n";
		echo "<table width=\"90%\" border=\"0\">\n";
		echo "<tr><td width=\"30%\"><b>Database Server:</b></td><td width=\"70%\"><input type=\"text\" name=\"config_server\"" . $noedit . " value=\"" . $config_server . "\" /></td></tr>\n";
		echo "<tr><td colspan=\"2\">This is the server that your database is hosted on. Usually it's just localhost.<br /><br /></td></tr>\n";
		echo "<tr><td width=\"30%\"><b>Database Name:</b></td><td width=\"70%\"><input type=\"text\" name=\"config_database\"" . $noedit . " value=\"" . $config_database . "\" /></td></tr>\n";
		echo "<tr><td colspan=\"2\">This is the name of the database you are connecting to.<br /><br /></td></tr>\n";
		echo "<tr><td width=\"30%\"><b>Database Username:</b></td><td width=\"70%\"><input type=\"text\" name=\"config_username\"" . $noedit . " value=\"" . $config_username . "\" /></td></tr>\n";
		echo "<tr><td colspan=\"2\">The username to login to your database with.<br /><br /></td></tr>\n";
		echo "<tr><td width=\"30%\"><b>Database Password:</b></td><td width=\"70%\"><input type=\"password\" name=\"config_password\"" . $noedit . " /></td></tr>\n";
		echo "<tr><td colspan=\"2\">The password to login to mySQL. You must re-enter this to update config.php.<br /><br /></td></tr>\n";
		echo "<tr><td width=\"30%\"><b>Secret Key:</b></td><td width=\"70%\"><input type=\"password\" name=\"secret_key\"" . $noedit . " value=\"" . $secret_key . "\" /></td></tr>\n";
		echo "<tr><td colspan=\"2\">An additional security feature. Set this as random characters/words to keep your users' accounts safe. If this is changed, any logged-in users will be logged out automatically, including you. <u>The longer the key, the better!</u><br /><br /></td></tr>\n";
		echo "<tr><td width=\"30%\"><b>Language:</b></td><td width=\"70%\"><select name=\"config_language\"" . $noedit . " style=\"width: 150px;\">\n";
		
		if ($handle = opendir('../languages'))
		{
			while (false !== ($file = readdir($handle)))
			{
				if (($file != ".") && ($file != "..") && is_file("../languages/" . $file))
				{
					$filename = explode(".php", $file);
					if ($filename[0] == $config_language)
					{
						echo "<option value=\"" . $filename[0] . "\" selected=\"selected\">" . $filename[0] . "</option>\n";
					}
					else
					{
						echo "<option value=\"" . $filename[0] . "\">" . $filename[0] . "</option>\n";
					}
				}
			}
			closedir($handle);
		}
		
		echo "</select></td></tr>\n";
		echo "<tr><td colspan=\"2\">The name of the language file that the game is using.<br /><br /></td></tr>\n";
		echo "<tr><td width=\"30%\"><b>Game Name:</b></td><td width=\"70%\"><input type=\"text\" name=\"config_name\"" . $noedit . " value=\"" . $config_name . "\" /></td></tr>\n";
		echo "<tr><td colspan=\"2\">What is your game called?<br /><br /></td></tr>\n";
		echo "<tr><td width=\"30%\"></td><td width=\"70%\"><input type=\"submit\"" . $noedit . " value=\"Edit!\" /></td></tr>\n";
		echo "</table>";
		echo "</form>\n";
		include("./footer.php");
		break;
}
?>