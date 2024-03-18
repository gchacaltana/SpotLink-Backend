<?php

namespace App\Helpers;

class NumberHelper
{
    public static function encodeBase62(int $num): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($chars);
        $encoded = '';

        do {
            $encoded = $chars[$num % $base] . $encoded;
            $num = (int)($num / $base);
        } while ($num > 0);

        return $encoded;
    }
}
