<?php

namespace THS\Commands\Sanction;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use THS\API\DiscordAPI;
use THS\API\LanguageAPI;
use pocketmine\Server;
use THS\Main;

class Freeze extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("freeze", $main);
        $this->setPermission("freeze.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("freeze.use")){
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (!isset($args[0])){
            LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) === null){
            LanguageAPI::sendMessage($player, "Le joueur indiqué n'est pas connecté !", "The indicated player is not connected!");
            return;
        }

        $sender = Server::getInstance()->getPlayer($args[0]);
        if ($sender->isImmobile()){
            $sender->setImmobile(false);
            LanguageAPI::sendMessage($sender, "Vous avez été unfreeze par§a {$player->getName()}", "You have been unfreeze by§a {$player->getName()}");
            LanguageAPI::sendMessage($player, "Vous venez d'unfreeze le joueur§a {$sender->getName()}", "You just unfreeze the player§a {$sender->getName()}");
            DiscordAPI::sendEmbed("Freeze", "{$sender->getName()} vient d'être unfreeze par {$player->getName()}");
        }else{
            $sender->setImmobile(true);
            LanguageAPI::sendMessage($sender, "Vous avez été freeze par§a {$player->getName()}", "You have been freeze by§a {$player->getName()}");
            LanguageAPI::sendMessage($player, "Vous venez d'freeze le joueur§a {$sender->getName()}", "You just freeze the player§a {$sender->getName()}");
            DiscordAPI::sendEmbed("Freeze", "{$sender->getName()} vient d'être freeze par {$player->getName()}");
        }
    }
}