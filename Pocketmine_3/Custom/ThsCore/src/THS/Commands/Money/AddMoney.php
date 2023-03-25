<?php

namespace THS\Commands\Money;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\API\MoneyAPI;
use THS\Main;

class AddMoney extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("addmoney", $main);
        $this->setPermission("addmoney.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("addmoney.use")){
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (!isset($args[0])){
            LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
            return;
        }

        if (!isset($args[1])) {
            LanguageAPI::sendMessage($player, "Vous devez indiquer un montant !", "You must enter an amount!");
            return;
        }

        if (!is_numeric($args[1])) {
            LanguageAPI::sendMessage($player,"Le montant ne doit pas comporter de lettres !", "The amount must not contain letters!");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) != null) {
            $sender = Server::getInstance()->getPlayer($args[0]);
            $name = $sender->getName();
        } else {
            $sender = $args[0];
            $name = $sender;
        }

        if (!MoneyAPI::exist($sender)) {
            LanguageAPI::sendMessage($player, "Le joueur§a $name §fn'existe pas !", "The player§a $name §fdo not exist !");
            return;
        }

        MoneyAPI::addMoney($sender, $args[1]);

        if (($sender instanceof Player) and ($sender->getName() != $player->getName())) {
            LanguageAPI::sendMessage($sender, "§a{$player->getName()} §fvient de vous ajouter §a$args[1]§f$ !", "§a{$player->getName()} §fjust added §a$args[1]§f$ !");
        }

        LanguageAPI::sendMessage($player, "Vous venez de ajouter§a $args[1]§f$ à §a$name §f!", "You just added§a $args[1]§f$ to §a$name §f!");
    }
}