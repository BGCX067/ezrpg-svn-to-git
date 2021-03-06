<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
Class: Module_Register
This module handles adding new players to the database.
*/
class Module_Register
{
	/*
	Variable: $db
	Contains the database object.
	*/
	private $db;
	
	/*
	Variable: $tpl
	The smarty object.
	*/
	private $tpl;
	
	/*
	Variable: $player
	The currently logged in player. Value is 0 if no user is logged in.
	*/
	private $player;
	
	/*
	Function: __construct
	Displays the registration form by default.
	
	Parameters:
	The parameters are passed by reference so that all modules and other code use the same objects.
	
	$db - An instance of the database class.
	$tpl - A Smarty object.
	$player - A player result set from the database, or 0 if not logged in.
	
	See Also:
	- <render>
	- <register>
	*/
	public function __construct(&$db, &$tpl, &$player=0)
	{
		if (LOGGED_IN)
		{
			header("Location: index.php");
			exit;
		}
		else
		{
			$this->db = $db;
			$this->tpl = $tpl;
			$this->player = $player;

			//If the form was submitted, process it in register().
			if ($_POST['register'])
				$this->register();
			else
				$this->render();
		}
	}
	
	/*
	Function: render
	Renders register.tpl.
	
	Also repopulates the form with submitted data if necessary.
	*/
	private function render()
	{
		//Add form default values
		if (!empty($_GET['username']))
			$this->tpl->assign('GET_USERNAME', $_GET['username']);
		if (!empty($_GET['email']))
			$this->tpl->assign('GET_EMAIL', $_GET['email']);
		if (!empty($_GET['email2']))
			$this->tpl->assign('GET_EMAIL2', $_GET['email2']);
		
		$this->tpl->display('register.tpl');
	}
	
	/*
	Function: register
	Processes the submitted player details.
	
	Checks if all the data is correct, and adds the player to the database.
	
	Otherwise, add an error message.
	
	At the end, use a *redirect* in order to be able to display a message through $_GET['msg'].
	*/
	private function register()
	{
		$error = 0;
		$errors = Array();
		
		//Check username
		$result = $this->db->fetchRow('SELECT COUNT(id) AS count FROM <ezrpg>players WHERE username=?', array($_POST['username']));
		if (empty($_POST['username']))
		{
			$errors[] = 'You didn\'t enter your username!';
			$error = 1;
		}
		else if (strlen($_POST['username']) < 3)
		{ //If username is too short...
			$errors[] = 'Your username must be longer than 3 characters!'; //Add to error message
			$error = 1; //Set error check
		}
		else if (!preg_match("/^[_a-zA-Z0-9]+$/", $_POST['username']))
		{ //If username contains illegal characters...
			$errors[] = 'Your username may contain only alphanumerical characters! (a-z, A-Z, 0-9)'; //Add to error message
			$error = 1; //Set error check
		}
		else if ($result->count > 0)
		{
			$errors[] = 'That username has already been used. Please create only one account!';
			$error = 1; //Set error check
		}
		
		//Check password
		if (empty($_POST['password']))
		{
			$errors[] = 'You didn\'t enter a password!';
			$error = 1;
		}
		else if (strlen($_POST['password']) < 3)
		{ //If password is too short...
			$errors[] = 'Your password must be longer than 3 characters!'; //Add to error message
			$error = 1; //Set error check
		}
		
		if ($_POST['password2'] != $_POST['password'])
		{
			$errors[] = 'You didn\'t verify your password correctly!';
			$error = 1;
		}
		
		//Check email
		$result = $this->db->fetchRow('SELECT COUNT(id) AS count FROM <ezrpg>players WHERE email=?', array($_POST['email']));
		if (empty($_POST['email']))
		{
			$errors[] = 'You didn\'t enter your email!';
			$error = 1;
		}
		else if (strlen($_POST['email']) < 3)
		{ //If email is too short...
			$errors[] = 'Your email must be longer than 3 characters!'; //Add to error message
			$error = 1; //Set error check
		}
		else if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email']))
		{
			$errors[] = 'Your email format is wrong!'; //Add to error message
			$error = 1; //Set error check
		}
		else if ($result->count > 0)
		{
			$errors[] = 'That email has already been used. Please create only one account, creating more than one account will get all your accounts deleted!';
			$error = 1; //Set error check
		}
		
		if ($_POST['email2'] != $_POST['email'])
		{
			$errors[] = 'You didn\'t verify your email correctly!';
			$error = 1;
		}
		
		//Check verification code
		if (empty($_POST['reg_verify']))
		{
			$errors[] = 'You didn\'t enter the verification code!';
			$error = 1;
		}
		else if ($_SESSION['verify_code'] != sha1(sha1(SECRET_KEY . strtoupper($_POST['reg_verify']) . SECRET_KEY2) . SECRET_KEY))
		{
			$errors[] = 'You didn\'t enter the correct verification code!';
			$error = 1;
		}
		
		//verify_code must NOT be used again.
		session_unset();
		session_destroy();
		
		
		if ($error == 0)
		{
			unset($insert);
			$insert = Array();
			//Add new user to database
			$insert['username'] = $_POST['username'];
			$insert['email'] = $_POST['email'];
			$insert['secret_key'] = createKey(1024);
			$insert['password'] = sha1(sha1($insert['secret_key'] . $_POST['password'] . SECRET_KEY2) . SECRET_KEY);
			$insert['registered'] = time();

			$new_player = $this->db->insert('<ezrpg>players', $insert);
			//Use $new_player to find their new ID number.

			$msg = 'Congratulations, you have registered! Please login now to play!';
			header('Location: index.php?msg=' . urlencode($msg));
			exit;
		}
		else
		{
			$msg = 'Sorry, there were some mistakes in your registration:<br />';
			$msg .= '<ul>';
			foreach($errors as $errmsg)
			{
				$msg .= '<li>' . $errmsg . '</li>';
			}
			$msg .= '</ul>';
			
			$url = 'index.php?mod=Register&msg=' . urlencode($msg)
					. '&username=' . urlencode($_POST['username'])
					. '&email=' . urlencode($_POST['email'])
					. '&email2=' . urlencode($_POST['email2']);
			header('Location: ' . $url);
			exit;
		}
	}
}
?>