<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*    http://www.ezrpgproject.com    */
/*************************************/

include("lib.php");

//Clear user's session data
session_unset();
session_destroy();

//Redirect to index
header("Location: index.php");
exit;
?>