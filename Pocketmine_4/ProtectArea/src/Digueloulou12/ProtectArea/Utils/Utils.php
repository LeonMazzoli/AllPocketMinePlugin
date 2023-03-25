<?php

namespace Digueloulou12\ProtectArea\Utils;

use Digueloulou12\ProtectArea\ProtectArea;

class Utils
{
    public static function getConfigValue(string $path, bool $nested = false): mixed
    {
        return $nested ? ProtectArea::getInstance()->getConfig()->getNested($path) : ProtectArea::getInstance()->getConfig()->get($path);
    }

    public static function getConfigReplace(string $path, array|string $re = [], array|string $r = [], bool $nested = false): string
    {
        return str_replace("{prefix}", self::getConfigValue("prefix"), str_replace($re, $r, self::getConfigValue($path, $nested)));
    }
}