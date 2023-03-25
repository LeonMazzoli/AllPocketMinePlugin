<?php

namespace Digueloulou12;

use Digueloulou12\Commands\TopDeath;
use Digueloulou12\Commands\TopKill;
use Digueloulou12\Events\KillDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class TopMain extends PluginBase
{
    private static TopMain $main;

    public function onEnable()
    {
        # Main
        self::$main = $this;

        # Config
        $this->saveResource("config.yml");

        # Events
        $this->getServer()->getPluginManager()->registerEvents(new KillDeathEvent(), $this);

        # Commands
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        if ($config->get("topkill_command") === true) $this->getServer()->getCommandMap()->register("", new TopKill());
        if ($config->get("topdeath_command") === true) $this->getServer()->getCommandMap()->register("", new TopDeath());
    }

    public static function getInstance(): TopMain
    {
        return self::$main;
    }
}