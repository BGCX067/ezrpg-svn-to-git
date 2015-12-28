<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://www.ezrpgproject.com    */
/*************************************/
include_once("lib.php");
$player = check_user($secret_key, $db);

$width = (isset($_GET['width']))?intval($_GET['width']):100;

$bar = new barGen();	// Load the class
$bar->setWidth($width);	// Set the width
$bar->setHeight(15);	// Set the height
$bar->setFontSize(3);	// Set the font size
$bar->setFileType($setting->general_bar_filetype); //Set the format of the image
$bar->makeBar();	// Start the bar

switch($_GET['type'])
{
	case "exp":
		$bar->setFillColor('blue'); //EXP is a blue bar
		$bar->setData($player->maxexp, $player->exp);	// Give the bar some values
		$bar->setTitle($lang['keyword_exp'] . ": ");
		break;
	case "hp":
		$percentage = ($player->hp / $player->maxhp) * 100;
		if ($percentage <= 30)
		{
			$bar->setFillColor('yellow');
		}
		else
		{
			$bar->setFillColor('green');
		}
		$bar->setData($player->maxhp, $player->hp);	// Give the bar some values
		$bar->setTitle($lang['keyword_hp'] . ": ");
		break;
	case "energy":
		$percentage = ($player->energy / $player->maxenergy) * 100;
		if ($percentage <= 30)
		{
			$bar->setFillColor('yellow');
		}
		else
		{
			$bar->setFillColor('green');
		}
		$bar->setData($player->maxenergy, $player->energy);	// Give the bar some values
		$bar->setTitle($lang['keyword_energy'] . ": ");
		break;
	default:
		break;
}

$bar->generateBar();	// Output the bar!

?>