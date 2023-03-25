<?php

namespace Digueloulou12;

use Digueloulou12\Events\StorageEvents;
use pocketmine\plugin\PluginBase;

class Storage extends PluginBase
{
    private static Storage $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new StorageEvents(), $this);
    }

    public static function getConfigValue(string $path): mixed
    {
        return self::$main->getConfig()->get($path);
    }

    public static function getInstance(): Storage
    {
        return self::$main;
    }
}