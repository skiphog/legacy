<?php
$result = $dbh->query('SELECT count(*) FROM complaints WHERE status = 0')->fetchColumn();
$total_new_moder2 = $result ?:'' ;
?>
<style>
#admin-menu ul{list-style: none;margin: 0;padding: 0;position: relative;width: 100px;transition: all .2s ease-in-out;-webkit-transition: all .2s ease-in-out;-moz-transition: all .2s ease-in-out;-ms-transition: all .2s ease-in-out;-o-transition: all .2s ease-in-out;}
#admin-menu ul li ul{opacity: 0;visibility: hidden;position:absolute;top: 0;left: 100px;margin: 0 0 0 20px;height:auto;width:100px}
#admin-menu ul li:hover >ul {opacity: 1;visibility: visible;margin: 0;}
</style>
<table width=235 cellspacing="0" cellpadding="0">
<tr>
<td align=center height=33 style="background: url('/img/tablehead.jpg') no-repeat;">
<b>МЕНЮ АДМИНА</b>
</td>
</tr>
<tr>
<td style="padding-left: 30px;">
<nav id="admin-menu">
    <ul>
        <li><a href="/adminpanel">Админ-панель</a></li>
		<li><a href="/adminrazdnews">Новости</a></li>
		<li><a href="/adminglobal">Настройки</a></li>
		<li><a href="/adminglobal?page=banners">Доп Настройки</a>
			<ul>
				<li><a href="/adminglobal?page=banners">Банеры</a></li>
				<li><a href="/adminglobal?page=present">Подарки</a></li>
				<li><a href="/adminglobal?page=rewards">Виджеты</a></li>
				<li><a href="/adminglobal?page=private">Приват</a></li>
				<li><a href="/adminglobal?page=disk">Диск</a></li>
			</ul>
		</li>
		<li><a href="/complaints?getOpenComplaints">Жалобы</a> <span class="count-complaints"><?php echo $total_new_moder2; ?></span></li>
        <li><a href="/dispatch">Рассылка</a></li>
	</ul>
</nav>
</td>
</tr>
</table>