<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Server;

class MainDuel extends PluginBase
{
    private static MainDuel $main;
    public static Config $config;

    public function onEnable()
    {
        self::$main = $this;

        $this->saveResource("config.yml");
        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI") === null) {
            Server::getInstance()->getLogger()->info("DuelUpgrade OFF, EconomyAPI not found !");
            Server::getInstance()->getPluginManager()->disablePlugin($this);
            return;
        }

        Server::getInstance()->getCommandMap()->register("", new CommandDuel());
        Server::getInstance()->getPluginManager()->registerEvents(new DuelEvents(), $this);
    }

    public static function getInstance(): MainDuel
    {
        return self::$main;
    }
}