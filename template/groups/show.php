<?php
/**
 * @var \System\View $this
 */

use App\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

[$g_id, $page] = request()->getValuesInteger(['id','page']);

$sql = 'select g.ug_title g_title,g.ug_descr g_content, g.ug_avatar g_avatar,g.ug_club g_club,g.ug_hidden g_hidden,
  u.id g_master_id,u.login g_master_login,u.gender g_master_gender,a.ugu_permission g_access,
  (select count(*) from ugusers where ugu_permission = 1 and ugroup_id = g.ugroup_id) g_cnt_user,
  (select count(*) from ugthread where ugt_dlt=0 and ugroup_id = g.ugroup_id) g_cnt_thread,
  (select sum(ug_comments_count) from ugthread where ugt_dlt=0 and ugroup_id = g.ugroup_id) g_cnt_message
from ugroup g
join users u on u.id = g.user_id
left join ugusers a on a.ugroup_id = g.ugroup_id and a.user_id = ' . $myrow->id . '
where g.ugroup_id = ' . $g_id . ' and g.ug_dlt = 0 limit 1';


if (!$group = $dbh->query($sql)->fetch()) {
    throw new NotFoundException('Группы не существует или удалена');
}

if ((int)$group['g_club'] === 1) {
    $group['g_belongs'] = $group['g_hidden'] ? 'Закрытый клуб' : 'Открытый клуб';
} else {
    $group['g_belongs'] = $group['g_hidden'] ? 'Закрытая группа' : 'Открытая группа';
}

$g_access = [
    'user'   => (int)$group['g_access'] === 1,
    'waiter' => $group['g_access'] === '0',
    'master' => (int)$group['g_master_id'] === $myrow->id
];
$g_access['access'] = !$group['g_hidden'] || $g_access['user'] || $g_access['master'];



if ($g_access['master']) {
    $sql = 'select count(*) from ugusers where ugroup_id = ' . $g_id . ' and ugu_permission = 0';
    $waiters = $dbh->query($sql)->fetchColumn();
}

if ($g_access['access'] || $myrow->isModerator()) {

    $sql = 'select count(*) from ugthread where ugroup_id = ' . $g_id . ' and ugt_dlt = 0';
    if ($count = $dbh->query($sql)->fetchColumn()) {
        $pagination = new Kilte\Pagination\Pagination($count, $page, 20, 2);

        $sql = 'select
          a.ugthread_id t_id,a.ugt_title t_title,a.ug_comments_count t_cnt,a.ugt_date t_date,a.polls_exist t_poll,
          a.v_count t_v_cnt,a.party t_party,a.sticky t_sticky,
          c.id t_master_id, d.ugc_date c_date, e.id c_master_id, e.login c_master_login,e.gender c_master_gender,
          if(d.ugcomments_id is null, a.ugt_date, d.ugc_date) sort_date
        from ugthread a
          join users c on a.user_id = c.id
          left join (
            select c1.ugthread_id, MAX( c1.ugcomments_id) max_id
            from ugcomments c1
              join ugthread t1 on t1.ugthread_id = c1.ugthread_id and t1.ugroup_id = ' . $g_id . '
            where c1.ugc_dlt = 0
            group by  t1.ugthread_id) b on  a.ugthread_id = b.ugthread_id
          left join ugcomments d on  a.ugthread_id = d.ugthread_id and d.ugcomments_id = b.max_id
          left join users e on d.user_id = e.id   
          where a.ugroup_id = ' . $g_id . ' and a.ugt_dlt = 0
        order by a.sticky desc , sort_date desc limit ' .$pagination->offset() . ', 20';

        $threads = $dbh->query($sql)->fetchAll();
        $paging = $pagination->build();

        $paging_page = '';
        if (!empty($paging)) {
            $paging_page = render('partials/paginate', [
                'paginate' => $paging,
                'link' => '/viewugroup_' . $g_id
            ]);
        }
    }
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?><?php echo html($group['g_title']); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?>Группа: <?php echo html($group['g_title']); ?><?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .g-breadcrumbs{font-style:italic;padding:5px;}
    .g-breadcrumbs,.g-info,.waiters,.g-content,.add-thread{margin-bottom:10px;}
    .g-left,.g-right{display:table-cell;width:100%;vertical-align:top;padding:0 5px;}
    .g-left h1{background:#ebebf3;margin:0 0 10px;padding:10px;}
    .g-right{text-align:center;}
    .g-info{width:200px;background:#ebebf3;padding:5px;}
    .g-status{font-weight:700;font-size:16px;margin:5px;}
    .g-content{background:#f7f7f7;font-size:16px;padding:10px;}
    .g-hidden-1,a.g-delete:hover{color:#FF0000;}
    .g-hidden-0{color:#008000;}
    table.g-info-small{text-align:left;overflow:hidden;margin:0 auto 10px;}
    table.g-info-small tr td:last-child{font-weight:700;}
    .btn-join{margin-bottom:5px;width:100%;}
    a.btn-join{width:90%;}
    a.btn:hover{color:#fff;}
    a.g-delete{color:#ccc;display:inline-block;margin:15px 0;}
    .msg-div{margin:5px auto;}
    .msg-time{display:block;float:right;}
    .msg-time,.msg-m-time{color:#A99898;font-size:.9em;}
    .msg-content{border:1px solid #DDDDE5;background-color:#FAFAFA;margin-bottom:5px;clear:both;padding:10px;}
    .msg-content h2{margin:0 0 10px;}
    .mcp-1{border:1px solid #ffa74d;background-color:#fff7df;}
    .mcw-1{border:1px solid #ff4d4d;background-color: #ffdfdf;}
    .msg-content img{vertical-align:middle;}
    .change-group{display:inline-block;float:right;margin:0 5px;}
    :focus{outline:none !important;}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div class="g-sheme">
    <div class="g-breadcrumbs">
        <a href="/ugrouplist" title="все группы">Все группы</a> &bull; <a href="/myugroups">Мои группы</a>
    </div>

    <div class="g-group">

        <div class="g-left">

            <h1 class="border-box"><?= html($group['g_title']) ?></h1>

            <div class="g-content border-box">
                <?php if(!empty($group['g_content'])) :?>
                    <?php echo smile(nl2br(hyperlink($group['g_content']))); ?>
                <?php else: ?>
                    Нет описания
                <?php endif; ?>
            </div>

            <div class="g-thread">
                <?php if($g_access['access'] || $myrow->isModerator()) {?>
                    <?php if($g_access['user'] || $g_access['master']) {?>
                        <div class="add-thread">
                            <a class="btn btn-primary" href="/addugthread_<?= $g_id ;?>_on">создать тему</a>
                        </div>
                    <?php }?>

                    <div>
                        <?php if(!empty($threads)) {?>
                            <div class="thread">
                                <?php foreach ($threads as $thread) {?>
                                    <div class="msg-div">
                                        <span class="msg-time">Тема создана: <?= date('d-m-Y H:i',strtotime($thread['t_date']));?> | <strong><?= (new \App\Components\SwingDate($thread['t_date']))->getHumans(); ?></strong></span>
                                        <div class="msg-content mcw-<?= $thread['t_sticky']?> mcp-<?= $thread['t_party']?>">
                                            <h2>
                                                <?php if($thread['t_sticky']) {?>
                                                    <img src="/img/group/warning.png" alt="warning" title="Прилеплена">
                                                <?php }?>
                                                <?php if($_SERVER['REQUEST_TIME'] - strtotime($thread['t_date']) < 86400) {?>
                                                    <img src="/img/group/new.png" alt="new" title="Новая тема">
                                                <?php }?>
                                                <?php if($thread['t_party']) {?>
                                                    <img src="/img/group/party.png" alt="party" title="Вечеринка">
                                                <?php }?>
                                                <?php if($thread['t_poll']) {?>
                                                    <img src="/img/group/polls.png" alt="polls" title="Голосование">
                                                <?php }?>
                                                <?php if(true === false) {?>
                                                    <img src="/img/group/hot.png" alt="hot" title="Горячая тема">
                                                <?php }?>
                                                <a href="/viewugthread_<?= $thread['t_id']?>_<?= ceil($thread['t_cnt'] / 20); ?>"><?= $thread['t_title']?></a>
                                            </h2>
                                            <div>
                                                Просмотров: <strong><?= $thread['t_v_cnt']; ?></strong>
                                                <?php if($thread['t_cnt']) {?>
                                                    Сообщений: <strong><?= $thread['t_cnt']; ?></strong>, последнее от
                                                    <a class="hover-tip" href="/id<?= $thread['c_master_id']; ?>" target="_blank">
                                                        <img src="/img/info_small_<?= $thread['c_master_gender']; ?>.png" width="15" height="14" alt="gender"> <?= $thread['c_master_login']; ?>
                                                    </a>
                                                    <br>
                                                    <span class="msg-m-time"><?= date('d-m-Y H:i',strtotime($thread['c_date']));?> | <?= (new \App\Components\SwingDate($thread['c_date']))->getHumans(); ?></span>
                                                <?php }else{?>
                                                    Сообщений нет
                                                <?php }?>
                                                <?php if($myrow->isModerator() || $g_access['master'] || $myrow->id === (int)$thread['t_master_id']) {?>
                                                    <a class="change-group" onclick="return window.confirm('Вы действительно хотите удалить тему?')" href="/delugthread_<?= $thread['t_id']?>" title="Удалить"><img src="/img/group/delete.png" width="23" height="23" alt="delete"></a>
                                                    <a class="change-group" href="/redugthread_<?= $thread['t_id']?>" title="Редактировать"><img src="/img/group/change.png" width="23" height="23" alt="change"></a>
                                                <?php }?>
                                                <?php if($myrow->isAdmin()) {?>
                                                    <form class="change-group" method="post" action="">
                                                        <input type="image" src="/img/group/g_warning.png" value="<?= $thread['t_id']?>:<?= (int)!$thread['t_sticky']?>" name="g_stiky" width="23" height="23" title="Закрепить">
                                                    </form>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                            <?php echo $paging_page; ?>
                        <?php }else{?>
                            <p>В группе нет тем</p>
                        <?php }?>
                    </div>


                <?php }else{?>
                    <div>
                        <?php if(!$g_access['waiter']) {?>
                            <p class="red">Для просмотра тем и сообщений требуется подать заявку на вступление.</p>
                            <a class="btn btn-success" href="/ugjoin_<?= $g_id; ?>" onclick="return window.confirm('Вы действительно хотите подать заявку на вступление в группу?')">Вступить</a>
                        <?php }else{?>
                            <p class="green">Вы подали заявку на вступление в группу. Дождитесь модерации.</p>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="g-right">
            <div class="g-info border-box">
                <img src="https://swing-kiska.ru/avatars/group_thumb/<?= $group['g_avatar']; ?>" alt="group_avatar">
                <div class="g-status">
                    <span class="g-hidden-<?= $group['g_hidden']; ?>"><?= $group['g_belongs']; ?></span>
                </div>

                <table class="g-info-small">
                    <tr>
                        <td>Участников:</td>
                        <td>
                            <?php if($group['g_cnt_user']) {?>
                                <a href="ugusers_<?= $g_id; ?>_1"><?= $group['g_cnt_user'] ?></a>
                            <?php }else{?>
                                0
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td>Тем:</td>
                        <td><?= $group['g_cnt_thread'] ?></td>
                    </tr>
                    <tr>
                        <td>Сообщений:</td>
                        <td><?= (int)$group['g_cnt_message'] ?></td>
                    </tr>
                    <tr>
                        <td>Модератор:</td>
                        <td>
                            <a class="hover-tip" href="/id<?= $group['g_master_id'];?>" target="_blank">
                                <img src="/img/info_small_<?= $group['g_master_gender'];?>.png" width="15" height="14" alt="gender" /> <?= $group['g_master_login'] ?>&nbsp;
                            </a>
                        </td>
                    </tr>
                </table>
                <?php if(!$g_access['master']) {?>
                    <?php if($g_access['user'] || $g_access['waiter']) {?>
                        <a class="g-delete" href="/ugjoin_<?= $g_id; ?>" onclick="return window.confirm('Вы действительно хотите выйти из группы?')">Выйти</a>
                    <?php }else{?>
                        <a class="btn btn-success btn-join" href="/ugjoin_<?= $g_id; ?>" onclick="return window.confirm('Вы действительно хотите стать участником группы?')">Вступить</a>
                    <?php }?>
                <?php }?>
            </div>

            <?php if($g_access['master']) {?>
                <?php if(!empty($waiters)) {?>
                    <div class="waiters">Ожидают модерации: <span class="red"><?= $waiters; ?></span></div>
                <?php }?>
                <a class="btn btn-primary btn-join" href="/redugroup_<?= $g_id; ?>">Управление</a>
            <?php }?>

            <?php if($g_access['master'] || $myrow->isModerator()) {?>
                <a class="g-delete" href="/delugroup_<?= $g_id; ?>" onclick="return window.confirm('Вы действительно хотите удалить группу?')">Удалить группу</a>
            <?php }?>

            <?php if($myrow->isAdmin()) {?>
                <form method="post" action="">
                    <?php if(!$group['g_club']) {?>
                        <button class="btn btn-default" name="add_club" value="<?= $g_id; ?>">Присвоить статус клуба</button>
                    <?php }else {?>
                        <button class="btn btn-default" name="del_club" value="<?= $g_id; ?>">Снять статус клуба</button>
                    <?php }?>
                </form>
            <?php }?>

        </div>
    </div>
</div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>