<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*         and CheapDevotion         */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("./lib.php");
define("PAGENAME", "Add Item");

if (check_permissions($player, 75) == 0) //GM rank must be 85+ to configurate the rest of the game
{
	include("./header.php");
	echo "Your GM rank is not high enough to access this page!";
	include("./footer.php");
	exit;
}

//array of item types - probably needs to be added to database
$itemtype[0] = "weapon";
$itemtype[1] = "armour";


$msg1 = "<font color=\"red\">"; //Name error?
$msg2 = "<font color=\"red\">"; //Description error?
$msg3 = "<font color=\"red\">"; //Type error?
$msg4 = "<font color=\"red\">"; //Effectiveness error?
$msg5 = "<font color=\"red\">"; //Price error?
$error = 0;

if ($_POST['add_item'])
{
	//Check if item name has already been used
	$query = $db->execute("select `id` from `blueprint_items` where `name`=?", array($_POST['name']));
	//Check name
	if (!$_POST['name']) { //If name isn't filled in...
		$msg1 .= "Please enter a name for the item." . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}

	else if (!preg_match("/^[a-z0-9]+([\\s]{1}[a-z0-9]|[a-z0-9])+$/i", $_POST['name']))
	{ //If name contains illegal characters...
		$msg1 .= "Please use aphanumeric characters only" . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}
	else if ($query->recordcount() > 0)
	{
		$msg1 .= "That item already exists. <br />\n";
		$error = 1; //Set error check
	}
	
	//Check description
	if (!$_POST['description']) { //If description isn't filled in...
		$msg2 .= "Please enter a description for the item." . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}

	//Check Item Type - Shouldn't ever be needed
	if (!$_POST['type']) { //If type isn't filled in...
		$msg3 .= "Please enter a type for the item." . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}

	
	//Check effectiveness
	if (!$_POST['effectiveness']) { //If effectiveness isn't filled in...
		$msg4 .= "Please enter the item's effectiveness." . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}

	else if (!is_numeric($_POST['effectiveness']))
	{ //If effectiveness is not a number...
		$msg4 .= "Item's Effectiveness must be a number" . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}	
	
	//Check price
	if (!$_POST['price']) { //If description isn't filled in...
		$msg5 .= "Please enter the item's price." . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}

	else if (!is_numeric($_POST['price']))
	{ //If price is not a number...
		$msg5 .= "The item's price must be a number" . "<br />\n"; //Add to error message
		$error = 1; //Set error check
	}	
	
	if ($error == 0)
	{
		$insert['name'] = $_POST['name'];
		$insert['description'] = $_POST['description'];
		$insert['type'] = $_POST['type'];
		$insert['effectiveness'] = $_POST['effectiveness'];
		$insert['price'] = $_POST['price'];
		$query = $db->autoexecute('blueprint_items', $insert, 'INSERT');
		
		
		if (!$query)
		{
			$could_not_register = "Could not add the item, the administrator has been notified. <br /><br />";
			
			$logmsg = "Player " . $player->username . " attempted to add and item, but an error occurred.";
			errorlog($logmsg, $db);
		}
		else
		{
			$insertid = $db->Insert_ID();
			
						}
			
			include("./header.php");
			echo $insert['name'] ." has been added to the database";
			echo "<div align='right'><INPUT TYPE=\"BUTTON\" VALUE=\"Back\" ONCLICK=\"window.location.href='add_item.php'\"></div>";
			include("./footer.php");
			exit;
		}
	}


$msg1 .= "</font>"; //Username error?
$msg2 .= "</font>"; //Password error?
$msg3 .= "</font>"; //Verify Password error?
$msg4 .= "</font>"; //Email error?
$msg5 .= "</font>"; //Verify Email error?

include("./header.php");

?>

<?=$could_not_register?>
<form method="POST" action="add_item.php">
<table width="100%">
<tr><td width="40%"><b>Name</b>:</td><td><input type="text" name="name" value="<?=$_POST['name'];?>" /></td></tr>
<tr><td colspan="2"><?=$msg1;?><br /></td></tr>

<tr><td width="40%"><b>Description</b>:</td><td><textarea name="description" cols="30" rows="5"><?=$_POST['description'];?></textarea></td></tr>
<tr><td colspan="2"><?=$msg2;?><br /></td></tr>

<tr><td width="40%"><b>Item Type</b>:</td><td><select name="type"><? 
// generates the dropdown options for item type
for($i=0; $i < count($itemtype); $i++)
   echo "<option value=". $itemtype[$i] .">" . $itemtype[$i] . "</option>";?></td></tr>
<tr><td colspan="2"><?=$msg3;?><br /></td></tr>

<tr><td width="40%"><b>Effectiveness</b>:</td><td><input type="text" name="effectiveness" value="<?=$_POST['effectiveness'];?>" /></td></tr>
<tr><td colspan="2"><?=$msg4;?><br /></td></tr>

<tr><td width="40%"><b>Price</b>:</td><td><input type="text" name="price" value="<?=$_POST['price'];?>" /></td></tr>
<tr><td colspan="2"><?=$msg5;?><br /></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="add_item" value="Add!"></td></tr>
</table>
</form>


<?php
include("./footer.php");
?>