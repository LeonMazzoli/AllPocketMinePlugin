<?php

namespace THS\Commands\Sanction;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Events\Chat;
use THS\Main;

class Unmute extends PluginCommand
{
    public function __construct(Main $main)
    {
        parent::__construct("unmute", $main);
        $this->setPermission("unmute.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("unmute.use")) {
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (!isset($args[0])) {
            LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) === null) {
            $sender = $args[0];
        } else {
            $senderr = Server::getInstance()->getPlayer($args[0]);
            $sender = $senderr->getName();
        }

        if (empty(Chat::$mute[$sender])){
            LanguageAPI::sendMessage($player, "Le joueur indiqué n'est pas mute !", "The indicated player is not mute!");
            return;
        }

        unset(Chat::$mute[$sender]);
        LanguageAPI::sendMessage($player, "Vous venez d'unmute§a $sender §f!", "You just unmute§a $sender §f!");
    }
}