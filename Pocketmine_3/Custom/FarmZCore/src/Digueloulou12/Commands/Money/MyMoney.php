<?php

namespace Digueloulou12\Commands\Money;

use Digueloulou12\API\MoneyAPI;
use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class MyMoney extends PluginCommand{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("mymoney"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("mymoney_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("mymoney"));
        if (isset($command[2])){
            if (!$player->hasPermission($command[2])){
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        $player->sendMessage(Main::getConfigAPI()->getConfigValue("mymoney_msg", [strtolower("{money}")], [MoneyAPI::getMoney($player)]));
    }
}