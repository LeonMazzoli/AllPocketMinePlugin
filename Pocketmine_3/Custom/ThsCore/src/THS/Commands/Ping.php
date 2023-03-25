<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Main;

class Ping extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("ping", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            LanguageAPI::sendMessage($player, "La commande doit être executer en jeu !", "");
            return;
        }

        if (isset($args[0]) and (Server::getInstance()->getPlayer($args[0]) === null)){
            LanguageAPI::sendMessage($player, "Le joueur indiqué n'est pas connecté !", "The indicated player is not connected!");
            return;
        }

        if (isset($args[0])){
            $sender = Server::getInstance()->getPlayer($args[0]);
        }else $sender = $player;

        $ping = $sender->getPing();

        LanguageAPI::sendMessage($player, "Le joueur§a {$sender->getName()} §fest à §a{$ping}§fms !", "The player§a {$sender->getName()} §fis at §a{$ping}§fms !");
    }
}