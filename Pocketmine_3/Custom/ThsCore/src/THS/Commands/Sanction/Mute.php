<?php

namespace THS\Commands\Sanction;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Events\Chat;
use THS\Main;

class Mute extends PluginCommand
{
    public function __construct(Main $main)
    {
        parent::__construct("mute", $main);
        $this->setPermission("mute.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("mute.use")) {
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (!isset($args[0])) {
            LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) === null) {
            LanguageAPI::sendMessage($player, "Le joueur indiqué n'est pas connecté !", "The indicated player is not connected!");
            return;
        }

        if (!isset($args[1])) {
            LanguageAPI::sendMessage($player, "Vous devez indiqué un temps ! (En minute)", "You must indicate a time! (In minutes)");
            return;
        }

        if (!is_numeric($args[1])) {
            LanguageAPI::sendMessage($player, "Vous devez indiqué un temps en minute !", "You must indicate a time in minutes!");
            return;
        }

        $reason = implode(" ", array_splice($args, 2, 99999));
        if (!isset($reason)) {
            LanguageAPI::sendMessage($player, "Vous devez indiqué une raison !", "You must indicate a reason!");
            return;
        }

        $sender = Server::getInstance()->getPlayer($args[0]);

        if (!isset(Chat::$mute[$sender->getName()]) or Chat::$mute[$sender->getName()] <= time()) {
            $time = $args[1] * 60;
            Chat::$mute[$sender->getName()] = time() + $time;
            LanguageAPI::sendAllMessage("Le joueur §a{$sender->getName()}§f vient d'être mute par §a{$player->getName()}§f pour§a $reason §f!", "The player §a{$sender->getName()}§f has just muted by §a{$player->getName()}§f for§a $reason §f!");
            LanguageAPI::sendMessage($player, "Vous venez de mute§a {$sender->getName()} §fpendant§a $args[1] §fminute(s) avec comme raison : §a$reason", "You just mute§a {$sender->getName()} §fduring§a $args[1] §fminute(s) with the reason : §a$reason");
            LanguageAPI::sendMessage($sender, "Vous avez été mute par §a{$player->getName()} §fpendant§a $args[1] §fminute(s) avec comme raison : §a$reason", "You have been mutated by§a {$player->getName()} §fduring§a $args[1] §fminute(s) with the reason : §a$reason");
        } else LanguageAPI::sendMessage($player, "Le joueur indiqué est déja mute !", "The indicated player is already mute!");
    }
}