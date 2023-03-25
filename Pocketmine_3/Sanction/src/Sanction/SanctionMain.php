<?php

namespace Sanction;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Sanction\Commands\Ban;
use Sanction\Commands\Banip;
use Sanction\Commands\Kick;
use Sanction\Commands\Mute;
use Sanction\Commands\Sanction;
use Sanction\Commands\Tban;
use Sanction\Commands\Unban;
use Sanction\Commands\Unmute;
use Sanction\Events\Chat;
use Sanction\Events\CommandExe;
use Sanction\Events\Join;

class SanctionMain extends PluginBase
{
    private static $main;

    public function onEnable()
    {
        // Message Load
        $this->getLogger()->info("Sanction on by Digueloulou12");

        self::$main = $this;

        // Config
        $this->saveResource("config.yml");
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);

        if ($config->get("version") != 3.5) {
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->error("Your configuration file is outdated.");
            $this->getLogger()->error("Your old configuration has been saved as config_old.yml and a new configuration file has been generated.");
        }

        // Commands
        $command = $this->getServer()->getCommandMap();

        $command->unregister($command->getCommand("kick"));
        $command->unregister($command->getCommand("ban"));
        $command->unregister($command->getCommand("pardon"));
        $command->unregister($command->getCommand("ban-ip"));
        $command->unregister($command->getCommand("pardon-ip"));
        $command->unregister($command->getCommand("banlist"));

        $command->register("kick", new Kick($this));
        $command->register("sanction", new Sanction($this));
        $command->register("tmute", new Mute($this));
        $command->register("unmute", new Unmute($this));
        $command->register("banip", new Banip($this));
        $command->register("tban", new Tban($this));
        $command->register("ban", new Ban($this));
        $command->register("unban", new Unban($this));

        // Events
        $this->getServer()->getPluginManager()->registerEvents(new Join(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Chat(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new CommandExe(), $this);
    }

    public function onDisable()
    {
        $this->getLogger()->info("Sanction off by Digueloulou12");
    }

    public static function getInstance(): SanctionMain
    {
        return self::$main;
    }

    public static function getConfigValue($value){
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        return $config->get($value);
    }
}