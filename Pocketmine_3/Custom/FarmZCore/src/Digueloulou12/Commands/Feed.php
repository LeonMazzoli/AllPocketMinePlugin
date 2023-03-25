<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Feed extends PluginCommand{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("feed"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("feed_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("feed"));
        if (isset($command[2])){
            if (!$player->hasPermission($command[2])){
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        $player->setFood(Main::getConfigAPI()->getConfigValue("food"));
        $player->setSaturation(Main::getConfigAPI()->getConfigValue("food_saturation"));
        $player->sendMessage(Main::getConfigAPI()->getConfigValue("feed_msg"));
    }
}