<?php

namespace App\Arrays;

/**
 * Class Gender
 *
 * @package App\Arrays
 */
class Genders
{
    public static $gender = [
        0 => '',
        1 => 'Мужчина',
        2 => 'Девушка',
        3 => 'Пара M+Ж',
        4 => 'Транс'
    ];

    public static $sgender = [
        1 => 'Мужчину',
        2 => 'Девушку',
        3 => 'Пару М+Ж',
        4 => 'Транса'
    ];

    public static $old = [
        0 => 'Был',
        1 => 'Был',
        2 => 'Была',
        3 => 'Были',
        4 => 'Был'
    ];

    public static $search = [
        0 => 'Я ищу',
        1 => 'Я ищу',
        2 => 'Я ищу',
        3 => 'Мы ищем',
        4 => 'Я ищу'
    ];
}
