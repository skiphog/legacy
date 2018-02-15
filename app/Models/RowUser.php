<?php

namespace Swing\Models;

use Swing\Components\SwingDate;

/**
 * Class RowUser
 *
 * @property SwingDate $birthday
 * @property string    $pic1
 * @property int       $photo_visibility
 * @property int       $visibility
 * @property string    $hot_time
 * @property string    $regdate
 * @property string    $now_status
 * @property string    $hot_text
 * @property int       $vipsmile
 * @property string    $fname
 * @property string    $about
 * @property SwingDate $last_view
 *
 * @package Swing\Models
 */
class RowUser extends User
{

    protected $hot;

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

        return $this->isHot() ? '#cad9ff' : '#fff';
    }

    /**
     * @return bool
     */
    public function isHot(): bool
    {
        if (null === $this->hot) {
            $this->hot = !empty($this->hot_text) && strtotime($this->hot_time) > $_SERVER['REQUEST_TIME'];
        }

        return $this->hot;
    }

    /**
     * @return bool
     */
    public function isNowStatus(): bool
    {
        return !empty($this->now_status);
    }

    /**
     * @return bool
     */
    public function isNewbe(): bool
    {
        return ($_SERVER['REQUEST_TIME'] - strtotime($this->regdate)) < 604800;
    }

    /**
     * @return bool
     */
    public function isBirthday(): bool
    {
        return $this->birthday->format('dm') === (new \DateTimeImmutable())->format('dm');
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        return ($this->last_view->getTimestamp() + 600) > $_SERVER['REQUEST_TIME'];
    }

    /**
     * @param $value
     */
    public function setBirthday($value): void
    {
        $this->{'birthday'} = new SwingDate($value);
    }

    /**
     * @param $value
     */
    public function setLastView($value): void
    {
        $this->{'last_view'} = new SwingDate($value);
    }
}
