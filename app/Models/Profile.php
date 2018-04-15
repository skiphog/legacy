<?php

namespace App\Models;

/**
 * Class Profile
 *
 * @property int $job
 * @property int $id_vip
 *
 * @package App\Models
 */
class Profile extends RowUser
{
    /**
     * @var int $cnt_friends
     */
    protected $cnt_friends;

    /**
     * @return string
     */
    public function getBackground(): string
    {
        if ($this->isVip()) {
            $sql = 'select v.background 
              from `option` o 
              left join vip_background v on v.id = o.vip_background 
            where o.u_id = ' . $this->id . ' limit 1';

            return 'url(' . (db()->query($sql)->fetchColumn() ?: '/img/vip.jpg') . ')';
        }

        return '#e1eaff';
    }

    /**
     * @return string
     */
    public function getVipSmile(): string
    {
        return \App\Arrays\VipSmiles::$array[$this->vipsmile];
    }

    /**
     * @return mixed
     */
    public function getGifterVip()
    {
        $sql = 'select login from users where id = ' . $this->id_vip;

        return db()->query($sql)->fetchColumn();
    }

    /**
     * @return int
     */
    public function getCountFriends(): int
    {
        if (null === $this->cnt_friends) {
            $sql = 'select count(*) from friends 
              where fr_kto = ' . $this->id . ' 
            and fr_podtv_kto = 1 and fr_podtv_kogo = 1';

            $this->cnt_friends = db()->query($sql)->fetchColumn();
        }

        return (int)$this->cnt_friends;
    }

    public function getFriends()
    {

    }
}
