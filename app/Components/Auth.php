<?php

namespace App\Components;

use App\Models\Myrow;

/**
 * Class Auth
 *
 * @package App\Components
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

    public static function quit(): void
    {
        unset($_SESSION['id'], $_SESSION['login'], $_SESSION['password']);

        $time = time() - 3600;
        $domain = config('domain');

        foreach (['id', 'login', 'password'] as $value) {
            setcookie($value, '', $time, '/', $domain);
        }
    }
}
