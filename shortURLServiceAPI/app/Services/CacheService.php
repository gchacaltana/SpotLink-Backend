<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

/**
 * Class TokenService
 * @package App\Services
 * Clase para manejar el acceso a la memoria cache.
 */
class CacheService
{
    public static function getLastTokenNumberByApp(string $appId): int
    {
        $hash = Redis::hgetall("app:" . $appId);
        if (gettype($hash) == "array" && count($hash) == 0) {
            self::createRangeTokenNumberByApp($appId);
        }
        return intval(Redis::hget("app:" . $appId, "last"));
    }

    public static function createRangeTokenNumberByApp(string $appID): void
    {
        $tokenNumberNewRange = self::getTokenNumberNewRange();
        $start = $tokenNumberNewRange;
        $end = $tokenNumberNewRange + self::getTokenRangeNumber() - 1;
        Redis::hset("app:" . $appID, "start", $start);
        Redis::hset("app:" . $appID, "end", $end);
        Redis::hset("app:" . $appID, "last", $start - 1);
        self::setTokenNumberNewRange($end + 1);
    }

    public static function setLastTokenNumberApp(string $appId, int $number)
    {
        Redis::hset("app:" . $appId, "last", $number);
    }

    public static function getEndRangeTokenNumberByApp(string $appId): int
    {
        return intval(Redis::hget("app:" . $appId, "end"));
    }

    public static function getTokenNumberNewRange(): int
    {
        self::initTokenNumberNewRange();
        return intval(Redis::get("token_last_new_range"));
    }

    private static function setTokenNumberNewRange(int $value)
    {
        Redis::set("token_last_new_range", $value);
    }

    private static function initTokenNumberNewRange(): void
    {
        if (is_null(Redis::get("token_last_new_range"))) {
            Redis::set("token_last_new_range", 10000000000);
        }
    }

    public static function getTokenRangeNumber(): int
    {
        if (is_null(Redis::get("token_range"))) {
            Redis::set("token_range", 20000);
        }
        return intval(Redis::get("token_range"));
    }

    public static function saveToken(string $token, string $url): void
    {
        Redis::set($token, $url);
    }

    public static function getToken(string $token): string|null
    {
        if (is_null(Redis::get($token))) {
            return null;
        }
        return Redis::get($token);
    }
}
