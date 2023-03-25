<?php

namespace FlyBlock;

use pocketmine\plugin\PluginBase;

class FlyMain extends PluginBase{
    private static $main;
    public function onEnable()
    {
        // Load
        $this->getLogger()->info("FlyBlock on by Digueloulou12");
        $this->saveDefaultConfig();

        // Main
        self::$main = $this;

        // Command
        $this->getServer()->getCommandMap()->register("flyblock", new Command($this));

        // Event
        $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);

        // Config
        if ($this->getConfig()->get("config") != 2){
            rename(FlyMain::getInstance()->getDataFolder()."config.yml", "old_config.yml");
            $this->saveDefaultConfig();
            $this->getLogger()->info("The configurations are not up to date! Please delete them and restart your server");
        }
    }

    public function onDisable()
    {
        // Unload
        $this->getLogger()->info("FlyBlock off by Digueloulou12");
    }

    public static function getInstance(): FlyMain{
        return self::$main;
    }
}