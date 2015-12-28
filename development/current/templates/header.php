<!-- Edit templates/header.php to edit the layout -->
<html>
<head>
<title><?=$config_name?> :: <?=PAGENAME?></title>
<link rel="stylesheet" type="text/css" href="./templates/style.css" />
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
<li><span class="header"><?=$lang['keyword_links']?></span></li>
<li><a href="index.php"><?=$lang['page_home']?></a></li>
<li><a href="register.php"><?=$lang['page_register']?></a></li>
</ul>
</div>

<div class="left-section">
<ul>
<li><span class="header"><?=$lang['keyword_other']?></span></li>
<li><a href="#">Vote for us!</a></li>
<li><a href="http://www.ezrpgproject.com/">ezRPG Project</a></li>
</ul>
</div>

<br /><br />

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">
<img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" />
</a>

</div>

<div id="right">
<div id="content">