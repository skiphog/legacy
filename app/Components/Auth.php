<?php

namespace Swing\Components;

use Swing\Models\Myrow;

/**
 * Class Auth
 *
 * @package Swing\Components
 */
class Auth
{
    /**
     * @var Myrow $user
     */
    protected static $user;

    /**
     * @return Myrow
     */
    public static function user(): Myrow
    {
        if (null === self::$user) {
            self::$user = self::init();
        }

        return self::$user;
    }

    /**
     * @return Myrow
     */
    protected static function init(): Myrow
    {
        if (isset($_SESSION['id'], $_SESSION['password'])) {
            return Myrow::getMyrow($_SESSION['id'], $_SESSION['password']) ?: new Myrow();
        }

        if (isset($_COOKIE['id'], $_COOKIE['password'])) {
            $user = Myrow::getMyrow($_COOKIE['id'], $_COOKIE['password']);

            if (!empty($user)) {
                $_SESSION['id'] = $user->id;
                $_SESSION['password'] = $user->password;
                $_SESSION['login'] = $user->login;

                return $user;
            }
        }

        return new Myrow();
    }
}
