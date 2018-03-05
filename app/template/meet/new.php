<?php
/**
 * @var \Swing\System\View $this
 */

$dbh = db();
$myrow = auth();

$sort = (int)request()->get('sort');
$join = '';

if ($sort !== 1 && $myrow->isUser()) {
    $join = ' and u.city = ' . $dbh->quote($myrow->city);
    switch ($sort) {
        case 3 :
            $join .= ' and gender = 3';
            break;
        case 4 :
            $join .= ' and gender = 1';
            break;
        case 5 :
            $join .= ' and gender = 2';
            break;
        default :
            $join .= '';
            break;
    }
}

$sql = /** @lang text */ 'select u.id, u.birthday, u.pic1, u.photo_visibility,
  u.real_status, u.visibility, u.hot_time, u.regdate,
  u.vip_time, u.now_status, u.hot_text,
  u.vipsmile,u.admin, u.moderator, u.city,
  u.login, u.fname, u.gender, u.about,
  ut.last_view  
    from users u 
      join users_timestamps ut on ut.id = u.id 
  where status = 1 ' . $join . ' order by u.regdate desc 
 limit 50';

$users = $dbh->query($sql)->fetchAll(PDO::FETCH_CLASS, \Swing\Models\RowUser::class);
?>
<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Новые анкеты<?php $this->stop(); ?>
<?php $this->start('description'); ?>Новые анкеты<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<table border=0 width=100%>

    <tr>
        <td height=1 colspan=2 bgcolor=#336699></td>
    </tr>
    <tr>
        <td align=left>
            <h1>Новые анкеты
                | <a href=newmeet_1>Все</a>
                <?php if ($myrow->isUser()) : ?>
                    | <a href="/newmeet_2">Все г. <?php echo html($myrow->city); ?></a>
                    | <a href="/newmeet_3">Пары г. <?php echo html($myrow->city); ?></a>
                    | <a href="/newmeet_4">М. г. <?php echo html($myrow->city); ?></a>
                    | <a href="/newmeet_5">Ж. г. <?php echo html($myrow->city); ?></a>
                <?php endif; ?>
            </h1>
        </td>
        <td align=right>

        </td>
    </tr>
    <tr>
        <td colspan=2 height=1 bgcolor=#336699></td>
    </tr>

    <tr>
        <td colspan=6 align=center valign=center bgcolor=#cad9ff>
            <b>Новые анкеты</b> (последние 50 зарегистрированных анкет)
        </td>
    </tr>


    <?php if(!empty($users)) : ?>
    <tr>
        <td>
            <?php foreach($users as $user) : ?>
                <?php anketa_usr_row($myrow, $user) ?>
            <?php endforeach; ?>
        </td>
    </tr>
    <?php else : ?>
    <tr>
        <td>
            Новеньких нет
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <td>

        </td>
    </tr>

    <tr>
        <td colspan=2 height=1 bgcolor=#336699></td>
    </tr>
</table>
<?php $this->stop(); ?>