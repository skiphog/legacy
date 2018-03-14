<?php

namespace App\Models;

use Detection\MobileDetect;

/**
 * Class Myrow
 *
 * @property string $password
 * @property int    $country
 * @property bool   $hidden_img
 * @property int    $stels
 *
 * @package App\Models
 */
class Myrow extends User
{
    /**
     * @param int    $id
     * @param string $password
     *
     * @return mixed
     */
    public static function getMyrow($id, $password)
    {
        $dbh = db();

        return $dbh->query('
          select u.id, u.admin, u.moderator, u.assistant, u.password, u.login, u.gender, u.city, u.country,
            u.status, u.rate, u.real_status, u.moder_text, u.vip_time, 
            o.hidden_img,o.stels
          from users u left join `option` o on o.u_id = u.id 
          where u.id =' . (int)$id . ' 
        and u.password = ' . $dbh->quote($password) . ' limit 1')->fetchObject(self::class);
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return isset($this->id);
    }

    /**
     * @return bool
     */
    public function isGuest(): bool
    {
        return !$this->isUser();
    }

    /**
     * @return bool
     */
    public function isStels(): bool
    {
        static $stels;

        if (null === $stels) {
            $stels = $this->isVip() && (bool)$this->stels;
        }

        return $stels;
    }

    /**
     * @return bool
     */
    public function isHiddenImg(): bool
    {
        return $this->isUser() && !(bool)$this->hidden_img;
    }

    /**
     * @return bool
     */
    public function isMobile(): bool
    {
        if (isset($_SESSION['mobile'])) {
            return (bool)$_SESSION['mobile'];
        }

        if (isset($_COOKIE['mobile'])) {
            return (bool)$_SESSION['mobile'] = (int)$_COOKIE['mobile'];
        }

        $detect = (int)(new MobileDetect())->isMobile();
        $_SESSION['mobile'] = $detect;
        setcookie('mobile', $detect, 0x7FFFFFFF, '/', config('domain'));

        return (bool)$detect;
    }

    /**
     * Обновить время на сайте
     */
    public function setTimeStamp(): void
    {
        if ($this->isUser()) {
            $cache = cache();
            if (!$this->isStels()) {
                $uonline = $cache->get('ts' . $this->id);

                if (!$uonline || (int)$uonline < ((int)$_SERVER['REQUEST_TIME'] - 600)) {
                    db()->exec('update users_timestamps 
                      set last_view = NOW(), ip = ' . request()->getClientIp2long() . ' 
                    where id = ' . $this->id);

                    $cache->delete('online_users');
                }
            }

            $cache->set('ts' . $this->id, (int)$_SERVER['REQUEST_TIME']);
        }
    }

    /**
     * Получить количество заявок в друзья
     *
     * @return string
     */
    public function getCountFriends(): string
    {
        $sql = 'select count(*) from friends where fr_kogo = ' . $this->id . ' and fr_podtv_kogo = 0';
        $count = db()->query($sql)->fetchColumn();

        return $count ? '<strong style="color: #F00">(+' . $count . ')</strong>' : '';
    }

    /**
     * Получить количество новых сообщений
     *
     * @return string
     */
    public function getCountMessage(): string
    {
        $sql = 'select count(*) from privat where pr_id_pol = ' . $this->id . ' and pr_pol_vis = 0';

        return db()->query($sql)->fetchColumn() ?: '';
    }

    /**
     * Получить количество новых уведомлений
     *
     * @return string
     */
    public function getCountNotify(): string
    {
        $sql = 'select count(*) from notification where id_user = ' . $this->id . ' and visibled = 0';

        return db()->query($sql)->fetchColumn() ?: '';
    }

    /**
     * Получить количество гостей
     *
     * @return string
     */
    public function getCountGuest(): string
    {
        $sql = 'select count(*) from whoisloock where wholoock_kogo = ' . $this->id . ' and looking = 0';

        return db()->query($sql)->fetchColumn() ?: '';
    }

    /**
     * @return mixed
     */
    public function getPrivateMessage()
    {
        $dbh = db();
        $sql = 'select count(*) from privat where pr_id_pol = ' . $this->id . ' and pr_pol_vis = 0';

        if(!$count = $dbh->query($sql)->fetchColumn()) {
            return null;
        }

        $sql = 'select p.pr_id_otp, u.login, pr_text, pr_time 
          from privat p 
          join users u on u.id = p.pr_id_otp 
          where p.pr_id_pol = ' . $this->id . ' 
          and p.pr_pol_vis = 0 
        order by p.pr_id desc limit 1';

        return [
            'count' => $count,
            'message' => $dbh->query($sql)->fetch()
        ];
    }

    /**
     * @return string|null
     */
    public function getSgender(): ?string
    {
        if ($this->isGuest()) {
            return null;
        }

        $sgender = db()->query('select sgender from users where id = ' . $this->id . ' limit 1')->fetchColumn();

        $sgender = array_filter(explode(',', $sgender), function ($v) {
            return (int)$v;
        });

        if (empty($sgender)) {
            $sgender = [1, 2, 3, 4];
        }

        return implode(',', $sgender);
    }
}
