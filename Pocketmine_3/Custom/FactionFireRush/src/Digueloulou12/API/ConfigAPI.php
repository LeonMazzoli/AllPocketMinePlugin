<?php

namespace Digueloulou12\API;

use Digueloulou12\Main;
use pocketmine\utils\Config;

class ConfigAPI
{
    public static function getConfig(): Config
    {
        return new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
    }

    public static function getConfigReplace(string $value, array $replace = [], array $replacer = []): string
    {
        $returnn = str_replace(strtolower("{prefix}"), self::getConfig()->get("prefix"), self::getConfig()->get($value));
        $return = str_replace($replace, $replacer, $returnn);
        return $return;
    }

    public static function getConfigValue(string $value)
    {
        return self::getConfig()->get($value);
    }
}