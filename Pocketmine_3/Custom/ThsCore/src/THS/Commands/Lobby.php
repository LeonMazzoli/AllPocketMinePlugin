<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Main;

class Lobby extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("lobby", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player) and !isset($args[0])){
            LanguageAPI::sendMessage($player, "La commande doit être executer en jeu !", "");
            return;
        }

        if (!$player->hasPermission("spy.use") and isset($args[0])){
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (isset($args[0]) and (Server::getInstance()->getPlayer($args[0]) === null)){
            LanguageAPI::sendMessage($player, "Le joueur sélectionné na pas été trouvé !", "The selected player was not found!");
            return;
        }

        if (isset($args[0])){
            $sender = Server::getInstance()->getPlayer($args[0]);
            LanguageAPI::sendMessage($player, "Vous venez de transferer§a {$sender->getName()} §fsur le lobby !", "You just transferred§a {$sender->getName()} §fon the lobby!");
            $sender->transfer("rutelia-mc.fr", 19132);
        }else $player->transfer("rutelia-mc.fr", 19132);
    }
}