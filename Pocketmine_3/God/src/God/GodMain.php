<?php

namespace God;

use pocketmine\plugin\PluginBase;

class GodMain extends PluginBase{
    private static $main;
    public function onEnable()
    {
        $this->getLogger()->info("God on by Digueloulou12");

        $this->saveDefaultConfig();

        self::$main = $this;

        $this->getServer()->getCommandMap()->register("god", new Command($this));

        $this->getServer()->getPluginManager()->registerEvents(new Event(), $this);
    }

    public function onDisable()
    {
        $this->getLogger()->info("God off by Digueloulou12");
    }

    public static function getInstance(): GodMain{
        return self::$main;
    }
}