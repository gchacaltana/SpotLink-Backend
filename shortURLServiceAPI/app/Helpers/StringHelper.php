<?php

namespace App\Helpers;

use App\Exceptions\TokenException;

class StringHelper
{
    public static function validateUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Si el formato del token no es alfanumérico, devuelve una excepción.
     * @param string $token
     * @throws TokenException
     */
    public static function validateTokenFormat(string $token)
    {
        if (!ctype_alnum($token)) {
            throw new TokenException('Token Format invalid');
        }
    }
}
