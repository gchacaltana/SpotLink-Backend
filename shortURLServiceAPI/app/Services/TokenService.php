<?php

namespace App\Services;

use App\Helpers\NumberHelper;
use App\Services\CacheService;

/**
 * Class TokenService
 * @package App\Services
 * Clase para manejar la generación y acceso de tokens (short link) por aplicaciones.
 */
class TokenService
{
    public static function generateToken(string $appId): string
    {
        $lastToken = CacheService::getLastTokenNumberByApp($appId);
        $endRange = CacheService::getEndRangeTokenNumberByApp($appId);
        $current = $lastToken + 1;
        if ($current == $endRange) {
            CacheService::createRangeTokenNumberByApp($appId);
        } else {
            CacheService::setLastTokenNumberApp($appId, $current);
        }
        $prefix = NumberHelper::encodeBase62(rand(1000, 9999));
        $correlative = NumberHelper::encodeBase62($current);
        return $prefix . $correlative;
    }
}
