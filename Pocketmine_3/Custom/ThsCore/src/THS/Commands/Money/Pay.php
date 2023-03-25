<?php

namespace THS\Commands\Money;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\API\MoneyAPI;
use THS\Main;

class Pay extends PluginCommand
{
    public function __construct(Main $main)
    {
        parent::__construct("pay", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            LanguageAPI::sendMessage($player, "La commande doit être executer en jeu !", "");
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
            LanguageAPI::sendMessage($player, "Le joueur§a $args[0] §fn'existe pas !", "The player§a $args[0] §fdo not exist !");
            return;
        }

        if ($name === $player->getName()) {
            LanguageAPI::sendMessage($player, "Vous ne pouvez pas vous payer !", "You can't afford it!");
            return;
        }

        if (MoneyAPI::myMoney($player) <= $args[1]) {
            LanguageAPI::sendMessage($player, "Vous n'avez pas assez d'argent !", "You do not have enough money !");
            return;
        }

        MoneyAPI::removeMoney($player, $args[1]);
        MoneyAPI::addMoney($sender, $args[1]);

        if ($sender instanceof Player) {
            LanguageAPI::sendMessage($sender, "Vous venez de recevoir§a $args[1]§f$ par §a{$player->getName()} §f!", "You just received§a $args[1]§f$ by §a{$player->getName()} §f!");
        }

        LanguageAPI::sendMessage($player, "Vous venez de payer§a $args[1]§f$ à §a$name §f!", "You just pay§a $args[1]§f$ to §a$name §f!");
    }
}