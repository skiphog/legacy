<?php
/**
 * @var \System\View $this
 * @var \App\Models\RowUser $user
 */

use App\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

[$user_id, $page] = request()->getValuesInteger(['user_id', 'page']);

$parse = new \App\Components\Parse\NoSession();
$paging_page = '';

$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility, u.real_status, u.visibility, u.hot_time, u.regdate, 
  u.vip_time, u.now_status, u.hot_text, u.vipsmile,u.admin, u.moderator, u.city, u.login, u.fname, u.gender, u.about, 
  ut.last_view
  from users u 
    join users_timestamps ut on ut.id = u.id 
  where u.id = ' . $user_id . '
limit 1';

if (!$user = $dbh->query($sql)->fetchObject(\App\Models\RowUser::class)) {
    throw new NotFoundException('Пользователя не существует');
}

$sql = 'select count(*) from diary where user_di = '. $user_id .' and deleted = 0';
if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 10, 2);

    $sql = 'select d.id_di, d.title_di, d.text_di, d.data_di, d.v_count
      from diary d
      where d.user_di = ' . $user_id . ' and d.deleted = 0 
    order by d.id_di desc limit ' . $pagination->offset() . ', 10';

    $sth = $dbh->query($sql);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $diaries = $sth->fetchAll();
    $paging = $pagination->build();

    if (!empty($paging)) {
        $paging_page = render('partials/paginate', ['paginate' => $paging, 'link' => '/diaries/user/' . $user_id]);
    }
}


?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Дневник <?php echo html($user->login); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?>Дневник <?php echo html($user->login); ?><?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
.my-diary {padding:5px;margin-bottom:20px;box-shadow:rgba(0,0,0,.09) 0 2px 0;}
.my-diary-header{margin-bottom:20px;}
.my-diary-title{margin:0;font-size:20px;}
.my-diary-control{margin:0;}
.my-diary-title>a{}
.my-diary-date{margin:0;color:#A99898;font-size:.9em;}
.diary-user{margin-bottom:20px}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<h2>Дневник <?php echo html($user->login); ?>
    <?php if ($count > 0) : ?>
        (<?php echo $count; ?>&nbsp;<?php echo plural($count, 'запись|записи|записей'); ?>)
    <?php endif; ?>
</h2>
<div class="diary-user">
    <?php anketa_usr_row($myrow, $user); ?>
</div>
<?php echo $paging_page; ?>
<div>
    <?php if (!empty($diaries)) : ?>
        <?php foreach ($diaries as $diary) : ?>
            <article class="my-diary">
                <header class="my-diary-header">
                    <h2 class="my-diary-title">
                        <a href="/viewdiary_<?php echo $diary['id_di']; ?>"><?php echo html($diary['title_di']) ?></a></h2>
                    <p class="my-diary-date">
                        <?php echo date('d-m-Y H:i', strtotime($diary['data_di'])); ?> |
                        <strong><?php echo (new \App\Components\SwingDate($diary['data_di']))->getHumans(); ?></strong>
                    </p>
                    <p class="my-diary-control">
                        Просмотров: <strong><?php echo $diary['v_count']; ?></strong>
                    </p>
                </header>
                <div><?php echo nl2br(imgart_no_reg(smile($parse->parse($diary['text_di'])))); ?></div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Записей нет</p>
    <?php endif; ?>
</div>
<?php echo $paging_page; ?>

<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>