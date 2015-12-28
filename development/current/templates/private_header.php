<!-- Edit templates/private_header.php to edit the layout -->
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
<b><?=$lang['keyword_username']?>:</b> <?=$player->username?><br />
<b><?=$lang['keyword_level']?>:</b> <?=$player->level?><br />
<?php
$percent = intval(($player->exp / $player->maxexp) * 100);
?>
<?=show_exp($player, $setting, $lang)?><br />
<?=show_hp($player, $setting, $lang)?><br />
<?=show_energy($player, $setting, $lang)?><br />
<b><?=$lang['keyword_gold']?>:</b> <?=$player->gold?><br />
</div>

<div class="left-section">
<ul>
<li><a class="header"><?=$lang['keyword_links']?></a></li>
<li><a href="home.php"><?=$lang['page_home']?></a></li>
<li><a href="log.php"><?=$lang['page_log']?> [<?=unread_log($player->id, $db)?>]</a></li>
<li><a href="inventory.php"><?=$lang['page_inventory']?></a></li>
<li><a href="bank.php"><?=$lang['page_bank']?></a></li>
<li><a href="hospital.php"><?=$lang['page_hospital']?></a></li>
<li><a href="battle.php"><?=$lang['page_battle']?></a></li>
<li><a href="shop.php"><?=$lang['page_shop']?></a></li>
</ul>
</div>

<div class="left-section">
<ul>
<li><a class="header"><?=$lang['keyword_community']?></a></li>
<li><a href="mail.php"><?=$lang['page_mail']?> [<?=unread_messages($player->id, $db)?>]</a></li>
<li><a href="members.php"><?=$lang['page_members']?></a></li>
<li><a href="#">Forum</a></li>
</div>

<div class="left-section">
<ul>
<li><a class="header"><?=$lang['keyword_other']?></a></li>
<li><a href="#">Help</a></li>
<li><a href="http://www.ezrpgproject.com/">ezRPG Project</a></li>
<li><a href="logout.php"><?=$lang['page_logout']?></a></li>
</ul>
</div>

<br /><br />

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">
<img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" />
</a>

</div>

<div id="right">
<div id="content">