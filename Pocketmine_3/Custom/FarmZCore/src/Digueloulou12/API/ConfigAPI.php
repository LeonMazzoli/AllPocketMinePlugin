<?php

namespace Digueloulou12\API;

use Digueloulou12\Main;
use pocketmine\utils\Config;

class ConfigAPI
{
    public function getConfigValue(string $configg, array $replace = [], array $replacer = [])
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $returnn = str_replace(strtolower("{prefix}"), Main::$prefix, $config->get($configg));
        $return = str_replace($replace, $replacer, $returnn);
        return $return;
    }

    public static function getConfigArray(string $path): array
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        return $config->get($path);
    }

    public static function getConfigInt(string $path): int
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        return $config->get($path);
    }
}