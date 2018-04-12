<?php
/**
 * @var \System\Controller $this
 */
?>
<?php if($myrow->isUser()) :?>
<style>
    .cnt{font-weight:900;text-shadow:none;}
    .count-mes{color:#961313;}
    .count-nof{color:#1F7900;}
    .count-guest{color: #ff6829;}
    div#info-privat{display:none;width:200px;position:fixed;bottom:15px;right:20px;margin:0 15px 10px 0;background:#EBEBF3;border-radius:4px;}
    div#info-privat a{color:#444;}
    div.close-info{margin-bottom:5px;font-weight:bolder;color:#00008b;}
    div.close-info>span{display:block;float:right;color:#000;margin-right:2px;cursor:pointer;}
    div.close-info>span:hover{color: #961313;}
</style>
<table width=235 cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 0 solid black;">
    <tr>
        <td align=center height=33 style="background: url('/img/tablehead.jpg') no-repeat;">
            <b>МОЙ ПРОФИЛЬ</b>
        </td>
    </tr>
    <tr>
        <td style="padding-left: 30px;">
            <a href="/id<?php echo $myrow->id; ?>">Моя страница</a> | <a href="/profile/edit"><span style="color:#961313">Редакт.</span></a>
            <br>
            <a href="/my/albums">Мои фотографии</a>
            <br>
            <a href="/my/friends">Мои друзья</a> <?php echo $myrow->getCountFriends(); ?>
            <br>
            <a href="/my/diaries">Мои дневники</a>
            <br>
            <a href="/my/dialogs?getNewMessage">Мои сообщения <span class="cnt count-mes"><?php echo $myrow->getCountMessage(); ?></span></a>
            <br>
            <a href="/my/dialogs?getNewNotification">Мои уведомления <span class="cnt count-nof"><?php echo $myrow->getCountNotify();?></span></a>
            <br>
            <a href="/my/guests">Мои гости <span class="cnt count-guest"><?php echo $myrow->getCountGuest();?></span></a>
            <br>
            <a href="/my/groups">Мои группы</a>
            <br>
            <a href="/my/groups/activity">Мои новости</a>
            <br>
            <br>
            <a href="/travel">Cвинг в путешествии</a>
            <br>
            <br>
            <a href="/test">Тест</a>
            <br>
            <br>
            <a href="/donate">Поддержать сайт</a>
            <?php if($myrow->isAdmin()) : ?>
                <br>
                <br>
                <a href="/services">Магазин / Сервисы</a>
            <?php endif; ?>
            <?php if(($adverts_3 = $cache->get('advert_3')) === false) {
                $sth = $dbh->query('select url, target, img from advert_baner where status = 1 and position = 3 order by date_start desc');
                if($sth->rowCount()) {
                    ob_start();
                    while ($row = $sth->fetch()) {?>
                        <br>
                        <br>
                        <a href="<?= $row['url']; ?>" target="<?= $row['target']; ?>">
                            <img class="border-box" style="padding: 0" src="/<?= $row['img']; ?>" width="170" alt="adi">
                        </a>
                    <?php }
                    $adverts_3 = compress(ob_get_clean());
                } else {
                    $adverts_3 = '';
                }
                $cache->set('advert_3', $adverts_3);
            }
            echo $adverts_3;
            ?>
            <br />
            <div id="vidget-o-global">
                <?php
                if($vigdet = $cache->get('vo')) {?>
                    <?php foreach($vigdet as $value) {?>
                        <br />
                        <a  href="<?php echo $value['linko'];?>" class="border-box" style="display: block;text-align: center;color: blue;width: 170px;background-color: #fff;border-radius: 4px">
                            <div style="display: table-cell;vertical-align: middle">
                                <img src="<?php echo $value['imageo'] ;?>" width="29" height="29" alt="vidget" style="vertical-align: middle" />
                            </div>
                            <div style="display: table-cell;width: 130px;padding: 0 2px;vertical-align: middle"><?php echo $value['texto']; ?></div>
                        </a>
                    <?php }?>
                <?php } ?>
            </div>
            <br />
            Вы вошли на сайт,
            <br />
            как <b><?php echo html($myrow->login);?></b>
            <br />
            <a href="/quit">Выход</a>
            <br />
            <br />
        </td>
    </tr>
</table>
<div id="info-privat" class="border-box"></div>
<?php else: ?>
<form method="post" action="/authentication">
		<table border=0 width=235>
			<tr>
				<td align=center height=33 style="background: url(/img/tablehead.jpg) no-repeat;">
					<strong>АВТОРИЗАЦИЯ</strong>
				</td>
			</tr>
			<tr>
				<td style="padding-left: 30px;">
					<label>Логин:</label>
					<br />
					<input class="input-main" style="width: 160px;padding: 5px" name="login" type="text" required="required" placeholder="Логин"/>
					<label>Пароль:</label>
					<br />
					<input class="input-main" style="width: 160px;padding: 5px" value="1" name="password" type="password" required="required" placeholder="Пароль"/>
				</td>
			</tr>
			<tr>
				<td style="padding-left: 30px;">
					<input id="check-1" name="save" type="checkbox" value="1" checked><label for="check-1">Запомнить меня</label>
					<br />
					<br />
					<input class="btn btn-default" type="submit" value="Войти" />
					<br />
					<br />
					<a href="/registration">Зарегистрироваться</a>
					<br />
					<a href="/repass">Напомнить пароль</a>
				</td>
			</tr>
		</table>
	</form>
<?php endif; ?>