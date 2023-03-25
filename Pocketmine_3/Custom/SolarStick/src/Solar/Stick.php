<?php

namespace Solar;

use pocketmine\plugin\PluginBase;
use Solar\Stick\Feed;
use Solar\Stick\Heal;
use Solar\Stick\Invisible;
use Solar\Stick\Speed;

class Stick extends PluginBase{
    private static $stick;
    public function onEnable()
    {
        $this->getLogger()->info("SolarStick on by Digueloulou12");

        $this->saveDefaultConfig();

        self::$stick = $this;

        $this->getServer()->getPluginManager()->registerEvents(new Feed(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Heal(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Speed(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Invisible(), $this);
    }

    public function onDisable()
    {
        $this->getLogger()->info("SolarStick off by Digueloulou12");
    }

    public static function getInstance(): Stick{
        return self::$stick;
    }
}