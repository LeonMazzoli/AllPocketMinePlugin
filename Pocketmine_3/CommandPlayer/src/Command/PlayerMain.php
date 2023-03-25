<?php

namespace Command;

use pocketmine\plugin\PluginBase;

class PlayerMain extends PluginBase{
    private static $main;
    public function onEnable()
    {
        $this->getServer()->getLogger()->info("CommandPlayer on by Digueloulou12");
        self::$main = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("command", new CommandPlayer($this));
    }

    public function onDisable()
    {
        $this->getServer()->getLogger()->info("CommandPlayer off by Digueloulou12");
    }

    public static function getInstance(): PlayerMain {
        return self::$main;
    }
}