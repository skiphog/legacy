<?php

namespace App\Components;

/**
 * Class SwingDate
 *
 * @package App\Components
 */
class SwingDate extends \DateTimeImmutable
{
    public static $month = [
        1  => 'Январь',
        2  => 'Февраль',
        3  => 'Март',
        4  => 'Апрель',
        5  => 'Май',
        6  => 'Июнь',
        7  => 'Июль',
        8  => 'Август',
        9  => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь'
    ];

    protected static $lang = [
        'год|года|лет',
        'месяц|месяца|месяцев',
        'день|дня|дней',
        'час|часа|часов',
        'минуту|минуты|минут'
    ];

    protected static $timeAgo = [
        'через ',
        ' назад'
    ];

    /**
     * @return string
     */
    public function getHumansShort(): string
    {
        return $this->getDateDiff('');
    }

    /**
     * @return string
     */
    public function getHumans(): string
    {
        return $this->getDateDiff(' назад');
    }

    /**
     * @return string
     */
    public function getHumansPerson(): string
    {
        $date = new \DateTimeImmutable();

        if ($this->format('d-m-Y') === $date->format('d-m-Y')) {
            return 'Сегодня';
        }

        if ($this->format('d-m-Y') === $date->modify('-1 day')->format('d-m-Y')) {
            return 'Вчера';
        }

        return $this->getHumans();
    }

    /**
     * @param string $word
     *
     * @return string
     */
    protected function getDateDiff(string $word): string
    {
        $diff = $this->getRealDateDiff();

        foreach ((array)$diff['date'] as $key => $value) {
            if ($value === 0) {
                continue;
            }

            [$one, $two, $tree] = explode('|', self::$lang[$key]);

            if ($value % 10 === 1 && $value % 100 !== 11) {
                $string = $value . ' ' . $one;
            } elseif ($value % 10 >= 2 && $value % 10 <= 4 && ($value % 100 < 10 || $value % 100 >= 20)) {
                $string = $value . ' ' . $two;
            } else {
                $string = $value . ' ' . $tree;
            }

            return $string . $word;
        }

        return 'Только что';
    }

    /**
     * Получить дату в формате MySql
     *
     * @return string
     */
    public function sqlFormat(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * @return array
     */
    protected function getRealDateDiff(): array
    {
        $date = $this->diff(new \DateTimeImmutable());

        return [
            'date'   => [
                $date->y,
                $date->m,
                $date->d,
                $date->h,
                $date->i,
            ],
            'invert' => $date->invert
        ];
    }
}
