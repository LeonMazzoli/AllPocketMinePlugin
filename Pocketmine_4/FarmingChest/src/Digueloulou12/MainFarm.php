<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;

class MainFarm extends PluginBase
{
    private static self $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents(new FarmingChest(), $this);
    }

    public static function getInstance(): self
    {
        return self::$main;
    }
}