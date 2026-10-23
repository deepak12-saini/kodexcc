<?php
declare(strict_types=1);

namespace App\Utility;

/**
 * Whole calendar days from the factory dispatch date.
 */
class ContainerTransit
{
    public static function days(mixed $from, mixed $to = null): int
    {
        $start = self::julian($from);
        $end = self::julian($to) ?? self::julian(date('Y-m-d'));
        if ($start === null || $end === null) {
            return 0;
        }

        return max(0, $end - $start);
    }

    public static function date(mixed $value): string
    {
        if (is_object($value) && method_exists($value, 'format')) {
            return $value->format('Y-m-d');
        }
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', (string)$value, $match)) {
            return $match[1] . '-' . $match[2] . '-' . $match[3];
        }

        return '';
    }

    public static function label(int $days): string
    {
        return $days . ' day' . ($days === 1 ? '' : 's');
    }

    private static function julian(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_object($value) && method_exists($value, 'format')) {
            $value = $value->format('Y-m-d');
        }
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})/', (string)$value, $match)) {
            return null;
        }

        return gregoriantojd((int)$match[2], (int)$match[3], (int)$match[1]);
    }
}
