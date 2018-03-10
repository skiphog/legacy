<?php
/**
 * @var \System\Controller $this
 */
?>
<style xmlns="http://www.w3.org/1999/html">
	.count-complaints,#moder-count,.moder-anons{color: #F00;font-weight:700;}
    #moder-count{font-size:16px}
</style>
<table width=235 cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 0 solid black;">
<tr>
<td align=center height=33 style="background: url('/img/tablehead.jpg') no-repeat;">
<b>МЕНЮ МОДЕРАТОРА</b>
</td>
</tr>
<tr>
<td style="padding-left: 30px;">
<?php

$result = $dbh->query('SELECT count(*) FROM users WHERE `status` = 3')->fetchColumn();
$total_new_moder = $result ?:'';

$result = $dbh->query('SELECT count(*) FROM `events` WHERE `status` = 3')->fetchColumn();
$total_new_moder1 = $result ?:'' ;



?> 
<a href="/moderator/list?action=moderations">Модерация</a> <span id="moder-count"><?= $total_new_moder; ?></span>
<br />
<a href="/moderator/list?action=photo">Фотографии</a>
<br />
<a href="/moderator/party">Анонсы</a> <span class="moder-anons"><?= $total_new_moder1; ?></span>
<br />
<a href="/banlist">Управление</a>
<br />
<a href="/modanket">Лог-лист</a>
<br />
<a href="/masters">Мастерская</a>
</td>
</tr>
</table>