<?php
/**
 * @var \Swing\System\View $this
 */

$dbh = db();
$myrow = user();

$users = [];
$page = (int)request()->get('page');

$sql = 'select count(*) 
  from users u 
  join users_timestamps ut on ut.id = u.id 
where u.status = 1 and ut.last_view > DATE_SUB(NOW(), interval ' . config('activity_time') . ' second)';

if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 25, 2);

    $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
    u.real_status, u.visibility, u.hot_time, u.regdate,
    u.vip_time, u.now_status, u.hot_text,
    u.vipsmile,u.admin, u.moderator, u.city,u.login, u.fname, u.gender, u.about,
    ut.last_view
      from users u 
        join users_timestamps ut on ut.id = u.id 
      where u.status=1 and ut.last_view > DATE_SUB(NOW(), interval ' . config('activity_time') . ' second) 
	order by ut.last_view desc limit ' . $pagination->offset() . ', 25';

    $sth = $dbh->query($sql);

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
                <li class="<?php echo $name; ?>"><a href="/onlinemeet_<?php echo $link; ?>"><?php echo $link; ?></a>
                </li>
            <?php } ?>
        </ul>
        <?php $paging_page = ob_get_clean();
    }} ?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Кто онлайн<?php $this->stop(); ?>
<?php $this->start('description'); ?>Кто онлайн<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<table border="0" width="100%">
    <tr>
        <td height="1" bgcolor="#336699"></td>
    </tr>
    <tr>
        <td align="left" style="font-weight:bolder;font-size:16px;">
            <!--suppress HtmlUnknownTarget -->
            <a href="/findlist">Поиск анкет</a>&nbsp;&bull;&nbsp;Кто он-лайн (<?php echo $count; ?>)
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
                    В данный момент в сети никого нет.
                </div>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td height="1" bgcolor="#336699"></td>
    </tr>
</table>
<?php $this->stop(); ?>