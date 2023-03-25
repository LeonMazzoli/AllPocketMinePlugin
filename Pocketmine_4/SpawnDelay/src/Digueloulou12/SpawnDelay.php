<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class SpawnDelay extends PluginBase
{
    private static SpawnDelay $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        $this->getServer()->getCommandMap()->register("", new SpawnCommand());
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::getConfigValue("prefix"), self::getConfigValue($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function getConfigValue(string $path): array|bool|int|string
    {
        $config = new Config(self::$main->getDataFolder() . "config.yml", Config::YAML);
        return $config->get($path);
    }

    public static function getInstance(): SpawnDelay
    {
        return self::$main;
    }
}