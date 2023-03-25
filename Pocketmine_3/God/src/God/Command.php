<?php

namespace God;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class Command extends PluginCommand{
    public static $god = [];
    public function __construct(GodMain $main)
    {
        $config = new Config(GodMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        parent::__construct("god", $main);
        $this->setPermission($config->get("perm"));
        $this->setDescription($config->get("desc"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(GodMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if (!$player->hasPermission($config->get("perm"))) return $player->sendMessage($config->get("noperm"));
        if (!($player instanceof Player)) return $player->sendMessage($config->get("ig"));


        if (isset(self::$god[$player->getName()])){
            unset(self::$god[$player->getName()]);
            $player->sendMessage($config->get("no"));
        }else{
            self::$god[$player->getName()] = $player;
            $player->sendMessage($config->get("yes"));
        }
        return true;
    }
}