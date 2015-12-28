<!-- Edit templates/header.php to edit the layout -->
<html>
<head>
<title>ezRPG :: <?=PAGENAME?></title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div id="wrapper">

<div id="header">
<div id="header-text">
<?=$config_name?>
</div>
</div>

<div id="left">
<div class="left-section">
<ul>
<li><span class="header">General</span></li>
<li><a href="index.php">GM Panel</a></li>
<li><a href="config.php">Game Config</a></li>
<li><a href="pages.php">Pages Config</a></li>
<li><a href="maintenance.php">Maintenance</a></li>
</ul>
</div>

<div class="left-section">
<ul>
<li><span class="header">Users</span></li>
<li><a href="members.php">Member List</a></li>
<li><a href="ban_member.php">Member Ban</a></li>
</ul>
</div>

<div class="left-section">
<ul>
<li><span class="header">Items</span></li>
<li><a href="weapons.php">Weapons</a></li>
<li><a href="armour.php">Armour</a></li>
</ul>
</div>

<div class="left-section">
<ul>
<li><span class="header">Stats</span></li>
<li><a href="stats.php">General Stats</a></li>
</ul>
</div>

<div class="left-section">
<ul>
<li><span class="header">Logs</span></li>
<li><a href="gmlog.php">GM Log</a></li>
<li><a href="errorlog.php">Error Log</a></li>
</ul>
</div>

<div class="left-section">
<ul>
<li><span class="header">Other</span></li>
<li><a href="../home.php">Back to Game</a></li>
<li><a href="../logout.php">Logout</a></li>
</ul>
</div>

<br /><br />

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">
<img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" />
</a>

</div>

<div id="right">
<div id="content">