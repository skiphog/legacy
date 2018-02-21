<?php
/**
 * @var \Swing\System\Controller $this
 */

$users = [];
$page = (int)$this->request->get('page');


$sgender = $myrow->getSgender();

$sql = 'select count(*) from users
	where city = ?
	and id <> ' . $myrow->id . '
	and status = 1
	and gender in (' . $sgender . ')
and sgender like \'%' . $myrow->gender . '%\'';

$sth = $dbh->prepare($sql);
$sth->execute([$myrow->city]);

if ($count = $sth->fetchColumn()) {

    $pagination = new Kilte\Pagination\Pagination($count, $page, 25, 2);

    $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
		u.real_status, u.visibility, u.hot_time, u.regdate,
		u.vip_time, u.now_status, u.hot_text,
		u.vipsmile,u.admin, u.moderator, u.city,u.login, u.fname, u.gender, u.about,
		ut.last_view
		from users u join users_timestamps ut on ut.id = u.id
		where u.city = ?
		and u.id <> ' . $myrow->id . '
		and u.status = 1
		and u.gender in (' . $sgender . ')
		and u.sgender like \'%' . $myrow->gender . '%\'
		order by ut.last_view
	desc limit ' . $pagination->offset() . ',25';

    $sth = $dbh->prepare($sql);
    $sth->execute([$myrow->city]);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $users = $sth->fetchAll(PDO::FETCH_CLASS, \Swing\Models\RowUser::class);
    $paging = $pagination->build();

    $paging_page = 'Одна страница';
    if (!empty($paging)) {
        ob_start(); ?>
        <ul class="pagination">
            <?php foreach ($paging as $link => $name) { ?>
                <li class="<?php echo $name; ?>"><a href="/nowmeet_<?php echo $link; ?>"><?php echo $link; ?></a></li>
            <?php } ?>
        </ul>
        <?php $paging_page = ob_get_clean();
    }} ?>

<table border="0" width="100%">
    <tr>
        <td height="1" bgcolor="#336699"></td>
    </tr>
    <tr>
        <td align="left" style="font-weight:bolder;font-size:16px;">
            <!--suppress HtmlUnknownTarget -->
            <a href="/findlist">Поиск анкет</a>&nbsp;&bull;&nbsp;Вас ищут (<?php echo $count; ?>)
        </td>
    </tr>
    <tr>
        <td height="1" bgcolor="#336699"></td>
    </tr>
    <?php if(!empty($users)) : ?>
        <tr>
            <td align="left"><?php echo $paging_page; ?></td>
        </tr>
        <tr>
            <td height="1" bgcolor="#336699"></td>
        </tr>
        <tr>
            <td align="center" class="user-row">
                <?php foreach ($users as $user) {
                    anketa_usr_row($myrow, $user);
                } ?>
            </td>
        </tr>
        <tr>
            <td height="1" bgcolor="#336699"></td>
        </tr>
        <tr>
            <td align="left"><?php echo $paging_page; ?></td>
        </tr>
    <?php else: ?>
    <tr>
        <td>
            <div style="text-align: center">
                <h2>К сожалению, по Вашему запросу ничего не найдено :(</h2>

                <p>Выборка сделана на оснавании данных в Вашей анктете и анкетах искомых Вами пользователей.</p>

                <p>Список постоянно меняется в зависимости от активности пользователей.</p>

                <p style="color: red">Пожалуйста, проверьте данные в <!--suppress HtmlUnknownTarget -->
                    <a href="/edit_profile">Вашей</a> анкете и повторите поиск.
                </p>
            </div>
        </td>
    </tr>
    <?php endif; ?>

    <tr>
        <td height="1" bgcolor="#336699"></td>
    </tr>
</table>