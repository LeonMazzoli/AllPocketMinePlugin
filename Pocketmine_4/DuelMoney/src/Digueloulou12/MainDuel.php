<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Server;

class MainDuel extends PluginBase
{
    private static MainDuel $main;
    public static Config $config;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();

        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        Server::getInstance()->getCommandMap()->register("", new CommandDuel());
        Server::getInstance()->getPluginManager()->registerEvents(new DuelEvents(), $this);
    }

    public static function getInstance(): MainDuel
    {
        return self::$main;
    }
}