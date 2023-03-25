<?php

namespace HealStick;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class HealMain extends PluginBase{
    private static $main;
    public function onEnable()
    {
        $this->getLogger()->info("HealStick on by Digueloulou12");

        Server::getInstance()->getPluginManager()->registerEvents(new HealStickItem(), $this);

        $this->saveDefaultConfig();

        self::$main = $this;
    }

    public function onDisable()
    {
        $this->getLogger()->info("HealStick off by Digueloulou12");
    }

    public static function getInstance(): HealMain{
        return self::$main;
    }
}