<?php

namespace Digueloulou12;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class MainTeam extends PluginBase implements Listener{
    /**
     * @var Config
     */
    public $config;
    private static $main;
    public function onEnable()
    {
        $this->saveResource("config.yml");
        $this->saveResource("data.json");

        $this->config = new Config($this->getDataFolder()."config.yml",Config::YAML);

        if ($this->config->get("version") !== 1) {
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->error("Your configuration file is outdated.");
            $this->getLogger()->error("Your old configuration has been saved as config_old.yml and a new configuration file has been generated.");
        }

        self::$main = $this;

        $command = explode(":", $this->config->get("command"));
        $this->getServer()->getCommandMap()->register($command[0], new ClanCommand($this));

        $this->getServer()->getPluginManager()->registerEvents(new ClanEvent(), $this);
    }

    public static function getInstance(): MainTeam{
        return self::$main;
    }
}