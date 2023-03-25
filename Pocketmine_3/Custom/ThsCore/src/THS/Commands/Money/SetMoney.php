<?php

namespace THS\Commands\Money;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\API\MoneyAPI;
use THS\Main;

class SetMoney extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("setmoney", $main);
        $this->setPermission("setmoney.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("setmoney.use")){
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

        MoneyAPI::setMoney($sender, $args[1]);

        if (($sender instanceof Player) and ($sender->getName() != $player->getName())) {
            LanguageAPI::sendMessage($sender, "§a{$player->getName()} §fvient de définir votre money à §a$args[1]§f$ !", "§a{$player->getName()} §fdefine your money §a$args[1]§f$ !");
        }

        LanguageAPI::sendMessage($player, "Vous venez de définir la money de§a $args[1]§f$ à §a$name §f!", "You just define the money of§a $args[1]§f$ to §a$name §f!");
    }
}