<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class MainKill extends PluginBase{
    private static $main;
    public function onEnable()
    {
        $this->saveResource("config.yml");
        self::$main = $this;

        Server::getInstance()->getPluginManager()->registerEvents(new KillEvent(), $this);
    }

    public static function getInstance(): MainKill{
        return self::$main;
    }
}