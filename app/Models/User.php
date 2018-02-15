<?php

namespace Swing\Models;

use Swing\System\Model;

/**
 * Class User
 *
 * @package Swing\Models
 *
 * @property int    $id
 * @property int    $admin
 * @property int    $moderator
 * @property int    $assistant
 * @property string $login
 * @property int    $gender
 * @property string $city
 * @property int    $status
 * @property int    $rate
 * @property bool   $real_status
 * @property string $moder_text
 * @property bool   $vip_time
 */
class User extends Model
{

    protected $vip;

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return (bool)$this->admin;
    }

    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        return (bool)$this->moderator;
    }

    /**
     * @return bool
     */
    public function isReal(): bool
    {
        return (bool)$this->real_status;
    }

    /**
     * @return bool
     */
    public function isVip(): bool
    {
        if (null === $this->vip) {
            $this->vip = strtotime($this->vip_time) - $_SERVER['REQUEST_TIME'] >= 0;
        }

        return $this->vip;
    }
}
