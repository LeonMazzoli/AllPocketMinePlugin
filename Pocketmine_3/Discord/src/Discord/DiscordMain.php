<?php

namespace Discord;

use Discord\Events\Chat;
use Discord\Events\Command;
use Discord\Events\Death;
use Discord\Events\Join;
use Discord\Events\Leave;
use pocketmine\plugin\PluginBase;

class DiscordMain extends PluginBase{
    private static $main;
    public function onEnable()
    {
        // Message Load
        $this->getLogger()->info("Discord on by Digueloulou12");

        // Config
        $this->saveDefaultConfig();

        // Events
        $this->getServer()->getPluginManager()->registerEvents(new Join(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Leave(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Death(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Chat(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Command(), $this);

        self::$main = $this;
    }

    public function onDisable()
    {
        // Message Unload
        $this->getLogger()->info("Discord off by Digueloulou12");
    }

    public static function getInstance(): DiscordMain{
        return self::$main;
    }
}