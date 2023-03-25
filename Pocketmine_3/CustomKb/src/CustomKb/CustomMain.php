<?php

namespace CustomKb;

use pocketmine\plugin\PluginBase;

class CustomMain extends PluginBase
{
    private static $main;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        if ($this->getConfig()->get("version") != 1) {
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->error("Your configuration file is outdated.");
            $this->getLogger()->error("Your old configuration has been saved as config_old.yml and a new configuration file has been generated.");
        }

        self::$main = $this;

        $command = explode(":", $this->getConfig()->get("command"));
        $this->getServer()->getCommandMap()->register($command[0], new CustomKbCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new CustomKbEvent(), $this);
    }

    public static function getInstance(): CustomMain
    {
        return self::$main;
    }
}