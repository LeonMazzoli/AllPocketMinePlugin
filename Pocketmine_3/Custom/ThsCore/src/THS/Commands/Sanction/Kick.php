<?php

namespace THS\Commands\Sanction;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Main;

class Kick extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("kick", $main);
        $this->setPermission("kick.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!isset($args[0])){
            LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) === null){
            LanguageAPI::sendMessage($player, "Le joueur indiqué n'est pas connecté !", "The indicated player is not connected!");
            return;
        }

        $reason = implode(" ", array_splice($args, 1, 99999));

        if (!isset($args[0])){
            LanguageAPI::sendMessage($player, "Vous devez indiqué une raison !", "You must indicate a reason");
            return;
        }

        $sender = Server::getInstance()->getPlayer($args[0]);

        if ($sender->hasPermission("kick")){
            LanguageAPI::sendMessage($player, "Vous ne pouvez pas kick un membre du staff !", "You can't kick a staff member!");
            return;
        }

        $sender->close("Vous avez été kick par§a {$player->getName()} §fpour§a $reason §f!", "Vous avez été kick par§a {$player->getName()} §fpour§a $reason §f!");
        LanguageAPI::sendAllMessage("Le joueur§a {$sender->getName()} §fvient d'être kick par §a{$player->getName()} §fpour§a $reason §f!", "The player {$sender->getName()} §fhas just kicked by §a{$player->getName()} §ffor§a $reason §f!");
        LanguageAPI::sendMessage($player, "Vous venez de kick le joueur  {$sender->getName()} !", "You have just kicked§a {$sender->getName()} §f!");
    }
}