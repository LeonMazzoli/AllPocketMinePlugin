<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;

class MainFarm extends PluginBase{
    private static $main;
    public function onEnable()
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents(new FarmingChest(), $this);
    }

    public static function getInstance(): MainFarm{
        return self::$main;
    }
}