<?php

namespace Digueloulou12\Commands\Money;

use Digueloulou12\API\MoneyAPI;
use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class AddMoney extends PluginCommand{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("addmoney"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("addmoney_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("addmoney"));
        if (isset($command[2])){
            if (!$player->hasPermission($command[2])){
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (!isset($args[0])){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_args_player"));
            return;
        }

        if (!MoneyAPI::existPlayer($args[0])){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_exist_player"));
            return;
        }

        if (!isset($args[1])){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
            return;
        }

        if (!is_numeric($args[1])){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
            return;
        }

        MoneyAPI::addMoney($args[0], $args[1]);
        $player->sendMessage(Main::getConfigAPI()->getConfigValue("addmoney_msg", [strtolower("{money}"), strtolower("{player}")], [$args[1], $args[0]]));
    }
}