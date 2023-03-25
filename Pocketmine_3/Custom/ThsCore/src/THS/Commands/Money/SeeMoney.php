<?php

namespace THS\Commands\Money;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\API\MoneyAPI;
use THS\Main;

class SeeMoney extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("seemoney", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!isset($args[0])){
            LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) !== null){
            $sender = Server::getInstance()->getPlayer($args[0]);
            $name = $sender->getName();
        }else {
            $sender = $args[0];
            $name = $sender;
        }

        if (!MoneyAPI::exist($sender)){
            LanguageAPI::sendMessage($player, "Le jouuer indiqué n'existe pas !", "The player indicated does not exist!");
            return;
        }

        $money = MoneyAPI::myMoney($sender);
        LanguageAPI::sendMessage($sender, "Le joueur§a $name §fà§a {$money}§f$ !", "The player§a $name §fhave§a {$money}§f$ !");
    }
}