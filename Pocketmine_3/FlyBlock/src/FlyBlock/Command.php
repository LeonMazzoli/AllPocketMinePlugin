<?php

namespace FlyBlock;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class Command extends PluginCommand{
    private $main;
    public static $fly = [];
    public function __construct(FlyMain $main)
    {
        $config = new Config(FlyMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        parent::__construct("flyblock", $main);
        $this->setDescription($config->get("desc"));
        $this->setPermission($config->get("perm"));
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = $this->getPlugin()->getConfig();
        if (!$player instanceof Player) return $player->sendMessage($config->get("console"));
        if (!$player->hasPermission($config->get("perm"))) return $player->sendMessage($config->get("noperm"));
        if (isset(self::$fly[$player->getName()])){ unset(self::$fly[$player->getName()]); $player->sendMessage($config->get("disable"));}
        else{ self::$fly[$player->getName()] = $player; $player->sendMessage($config->get("enable"));}
        return true;
    }
}