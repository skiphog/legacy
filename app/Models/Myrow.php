<?php

namespace Swing\Models;

use Detection\MobileDetect;

/**
 * Class Myrow
 *
 * @property string $password
 * @property int    $country
 * @property bool   $hidden_img
 * @property int    $stels
 *
 * @package Swing\Models
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
                      set last_view = NOW(), ip = ' . ip2long($_SERVER['REMOTE_ADDR']) . ' 
                    where id = ' . $this->id);

                    $cache->delete('online_users');
                }
            }

            $cache->set('ts' . $this->id, (int)$_SERVER['REQUEST_TIME']);
        }
    }
}
