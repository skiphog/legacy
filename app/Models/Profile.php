<?php

namespace App\Models;

/**
 * Class Profile
 *
 * @property int $job
 *
 * @package App\Models
 */
class Profile extends RowUser
{

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

    public function getRole()
    {

    }
}
