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

        .profile-header {
            padding: 5px;
            border-bottom: 5px solid #fff;
        }

        .profile-header-login {
            font-size: 18px;
            font-weight: 700
        }

        .profile-table {
            display: table-cell;
        }

        .profile-left {
            width: 250px;
            border-right: 5px solid #fff;
        }

        .profile-avatar {
            background: #fff;
            border-bottom: 5px solid #fff;
        }

        .profile-avatar img {
            width: 250px;
            padding: 2px;
            border: 1px solid #ccc;
            vertical-align: middle;
        }

        .relevance {
            width: 255px;
            font-size: 0;
            margin: 0 auto;
            background: #fff;
        }

        input[name=relevance] {
            position: absolute;
            width: 1px;
            height: 1px;
            margin: -1px;
            border: 0;
            padding: 0;
            white-space: nowrap;
            -webkit-clip-path: inset(100%);
            clip-path: inset(100%);
            clip: rect(0 0 0 0);
            overflow: hidden;
        }

        .relevance::after {
            content: "";
            display: table;
            clear: both;
        }

        .relevance label {
            display: inline-block;
            width: 80px;
            height: 35px;
            font-size: 12px;
            padding: 5px 2px;
            overflow: hidden;
            vertical-align: middle;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, .2);
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            margin-left: -1px;
            -webkit-transition: all .1s;
            -o-transition: all .1s;
            transition: all .1s;
        }

        .relevance label:hover {
            -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, .1);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, .1);
        }

        .relevance label:active {
            -webkit-box-shadow: inset 0 2px 8px rgba(0, 0, 0, .2);
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, .2);
        }

        .relevance input[type=radio]:checked + label {
            color: #ffffff;
            -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, .2);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, .2);
        }

        .relevance input[type=radio]:checked + label[for=rev2] {
            background-color: #24b662;
        }

        .relevance input[type=radio]:checked + label[for=rev1] {
            background-color: #e8b222;
        }

        .relevance input[type=radio]:checked + label[for=rev0] {
            background-color: #a52a2a;
        }
        .profile-panel {
            border-top: 15px solid #fff;
        }
        .profile-panel-header{
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
        }
        .profile-panel-content{
            background: #fff;
            padding: 5px;
            border: 1px solid #ccc;
        }
    </style>
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
                <div class="profile-panel-header">Баллы и бонусы</div>
                <div class="profile-panel-content">
                    <div>Баллы: <strong><?php echo $user->rate; ?></strong></div>

                </div>
            </div>
        </div>
        <div class="profile-table"></div>
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