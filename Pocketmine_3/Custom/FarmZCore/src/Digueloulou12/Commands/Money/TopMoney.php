<?php

namespace Digueloulou12\Commands\Money;

use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class TopMoney extends PluginCommand{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("topmoney"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("topmoney_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("topmoney"));
        if (isset($command[2])){
            if (!$player->hasPermission($command[2])){
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        $player->sendMessage(Main::getConfigAPI()->getConfigValue("topmoney_title"));

        $i = 1;
        $money = [];
        foreach (Main::$players->getAll() as $players => $key){
            $money[$players] = $key["Money"];
        }

        arsort($money);
        foreach ($money as $name => $value) {
            if ($i === Main::$config->get("tommoney_num") + 1) {
                break;
            } else {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("topmoney_msg", [strtolower("{num}"), strtolower("{player}"), strtolower("{money}")], [$i, $name, $value]));
                $i++;
            }
        }
    }
}