<?php

namespace App\Models;

use App\System\Model;

/**
 * Class User
 *
 * @package App\Models
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
    /**
     * Статус Активно
     */
    public const ACTIVE = 1;

    /**
     * Статус не активно
     */
    public const INACTIVE = 2;

    /**
     * Статус на модерации
     */
    public const ON_MODERATION = 3;

    /**
     * Забанен
     */
    public const BAN = '_БАН_';

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

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return self::ACTIVE === $this->status;
    }

    /**
     * @return bool
     */
    public function isInActive(): bool
    {
        return self::INACTIVE === $this->status;
    }

    /**
     * @return bool
     */
    public function isOnModeration(): bool
    {
        return self::ON_MODERATION === $this->status;
    }
}
