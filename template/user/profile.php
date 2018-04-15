<?php
/**
 * @var \System\View $this
 */

use App\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

$sql = 'select u.id, u.admin, u.moderator,u.assistant, u.login, u.fname, u.birthday,
  u.gender, u.purposes, u.email, u.country, u.city, u.marstat,
  u.child, u.height, u.weight, u.hcolor, u.ecolor, u.etnicity,
  u.religion, u.smoke, u.drink, u.education, u.job, u.hobby,
  u.about, u.about_search, u.sgender, u.setnicity, u.sreligion,
  u.agef, u.aget, u.heightf, u.heightt, u.weightf, u.weightt,
  u.pic1, u.regdate, u.status, u.rate, u.real_status, u.visibility,
  u.photo_visibility, u.now_status, u.moder_text, u.moder_zametka,
  u.vipsmile, u.vip_time,u.id_vip, u.por_login,HEX(uuid) as uuid,
  ut.last_view, ut.ip
from users u
  join users_timestamps ut on ut.id = u.id
where u.id = ' . $myrow->id;

/** @var \App\Models\Profile $user */
if (false === $user = $dbh->query($sql)->fetchObject(\App\Models\Profile::class)) {
    throw new NotFoundException('Пользователя не существует');
}


?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Моя страница<?php $this->stop(); ?>
<?php $this->start('description'); ?>Моя страница<?php $this->stop(); ?>

<?php $this->start('style'); ?>
    <style>
        .profile {
            background: <?php echo $user->getBackground(); ?>
        }
    </style>
    <link rel="stylesheet" href="/css/profile.css">
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
    <div class="profile">
        <div class="profile-header">
            <?php if ($user->isVip()) : ?>
                <img src="<?php echo $user->getVipSmile(); ?>">
            <?php endif; ?>
            <?php if ($user->isBirthday()) : ?>
                <img src="/img/dr.gif" width="19" height="23" alt="birthday">
            <?php endif; ?>
            <?php if ($user->isBirthday()) : ?>
                <img src="/img/dr.gif" width="19" height="23" alt="birthday">
            <?php endif; ?>
            <?php if ($user->isReal()) : ?>
                <img src="/img/real.gif" width="20" height="20" alt="real">
            <?php endif; ?>
            <span class="profile-header-login">
                <span class="moderator-<?php echo $user->moderator; ?> admin-<?php echo $user->admin; ?>"></span>
                <?php echo html($user->login); ?>
            </span>
            <?php if ($user->isOnline()) : ?>
                <span style="padding:0 2px;background-color:#F00;color:#FFF;">В сети</span>
            <?php else: ?>
                <?php echo \App\Arrays\Genders::$old[$user->gender]; ?> на сайте:
                <b><?php echo $user->last_view->getHumansPerson(); ?></b>
            <?php endif; ?>
        </div>
        <div class="profile-table profile-left">
            <div class="profile-avatar">
                <img src="/avatars/user_pic/<?php echo $user->pic1; ?>" width="250" alt="avatar">
            </div>
            <div class="relevance">
                <?php foreach (\App\Arrays\Stats::$relevance as $key => $value) : ?>
                    <input id="rev<?php echo $key; ?>" type="radio" name="relevance" value="<?php echo $key; ?>"
                        <?php if ($key === $user->job) : ?> checked<?php endif; ?>>
                    <label for="rev<?php echo $key; ?>"><?php echo $value; ?></label>
                <?php endforeach; ?>
            </div>
            <div class="profile-panel">
                <div class="profile-panel-header">Информация</div>
                <div class="profile-panel-content">
                    <div class="profile-panel-info">Ваши баллы:
                        <strong><?php echo number_format($user->rate, 0, '', ' '); ?></strong>
                    </div>
                    <?php if ($user->isVip()) : ?>
                        <div class="profile-panel-info">
                            <img src="/img/vip1.gif" width="17" height="16" alt="vip"> действует:
                            <strong><?php echo (new \App\Components\SwingDate($user->vip_time))->getHumansShort(); ?></strong>
                        </div>
                        <?php if (!empty($user->id_vip)) : ?>
                            <div class="profile-panel-info">
                                VIP подарил: <a href="/id<?php echo $user->id_vip; ?>">
                                    <strong><?php echo html($user->getGifterVip()); ?></strong>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <hr class="profile-hr">
                    <div class="profile-panel-info">
                        <a>Как получить баллы?</a><br>
                        <a>Как получить статус реальности?</a><br>
                        <a>Как получить VIP статус?</a>
                    </div>
                    <hr class="profile-hr">
                    <div class="profile-panel-info">
                        <a>Администрация</a>
                    </div>
                </div>
                <div></div>
            </div>
            <div class="profile-panel">
                <div class="profile-panel-header">
                    Друзья
                    <?php if(!empty($cnt_friends = $user->getCountFriends())) : ?>
                        <a href="/my/friends">(<?php echo $cnt_friends; ?>)</a>
                    <?php endif; ?>
                </div>
                <div class="profile-panel-content">
                    <?php if(!empty($cnt_friends)) : ?>

                    <?php else : ?>
                        <div class="profile-panel-info">
                            У вас пока нет друзей
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="profile-table">
            <?php var_dump($user); ?>
        </div>
        <?php /*var_dump($user) */ ?>
    </div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
    <script>
      var t_rel;
      $('.relevance').on('change', 'input[name=relevance]', function () {
        var relevance = +$(this).val();
        clearTimeout(t_rel);
        t_rel = setTimeout(function () {
          $.post('/ajax/', {cntr: 'Option', action: 'saveRelevance', relevance: relevance});
        }, 500);
      });
    </script>
<?php $this->stop(); ?>