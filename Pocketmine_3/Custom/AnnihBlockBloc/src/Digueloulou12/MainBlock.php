<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;

class MainBlock extends PluginBase{
    private static $main;
    public function onEnable()
    {
        $this->saveResource("config.yml");

        self::$main = $this;

        $this->getServer()->getPluginManager()->registerEvents(new BreakBlock(), $this);
    }

    public static function getInstance(): MainBlock{
        return self::$main;
    }
}