<?php

namespace Digueloulou12;

use Digueloulou12\Events\BlockEvent;
use Digueloulou12\Events\DamageEvent;
use Digueloulou12\Events\FoodEvent;
use Digueloulou12\Events\InteractEvent;
use Digueloulou12\Events\JoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class LobbyMain extends PluginBase{
    private static $main;
    public function onEnable()
    {
        $this->getLogger()->info("LobbySystem by Digueloulou12");

        $this->saveResource("config.yml");

        self::$main = $this;

        Server::getInstance()->getPluginManager()->registerEvents(new FoodEvent(), $this);
        Server::getInstance()->getPluginManager()->registerEvents(new JoinEvent(), $this);
        Server::getInstance()->getPluginManager()->registerEvents(new BlockEvent(), $this);
        Server::getInstance()->getPluginManager()->registerEvents(new DamageEvent(), $this);
        Server::getInstance()->getPluginManager()->registerEvents(new InteractEvent(), $this);
    }

    public static function getInstance(): LobbyMain{
        return self::$main;
    }

    public static function getConfigValue(string $value){
        $config = new Config(LobbyMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        return $config->get($value);
    }
}