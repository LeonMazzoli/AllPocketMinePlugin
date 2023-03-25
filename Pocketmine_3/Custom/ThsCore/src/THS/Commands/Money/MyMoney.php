<?php

namespace THS\Commands\Money;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use THS\API\LanguageAPI;
use THS\API\MoneyAPI;
use THS\Main;

class MyMoney extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("mymoney", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            $player->sendMessage("La commande doit être executer en jeu !");
            return;
        }

        $money = MoneyAPI::myMoney($player);
        LanguageAPI::sendMessage($player, "Vous avez§a {$money}§f$ !", "You have§a {$money}§f$ !");
    }
}