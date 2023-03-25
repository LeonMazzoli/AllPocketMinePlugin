<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\Main;

class Gamemode extends PluginCommand
{
    public function __construct(Main $main)
    {
        parent::__construct("gamemode", $main);
        $this->setPermission("gamemode.use");
        $this->setAliases(["gm"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("gamemode.use")) return $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission d'utiliser cette commande !");
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix . "Vous devez indiqué un gamemode !");
        if (isset($args[0]) and !isset($args[1]) and !($player instanceof Player)) return $player->sendMessage(Main::$prefix . "La commande doit être executer en jeu !");
        if (isset($args[1]) and Server::getInstance()->getPlayer($args[1]) === null) return $player->sendMessage(Main::$prefix . "Le joueur sélectionnné na pas été trouvé !");


        if (isset($args[1])) {
            $sender = Server::getInstance()->getPlayer($args[1]);
            if ($args[0] === "c" or $args[0] === "1" or $args[0] === "creative") {
                $sender->setGamemode(1);
                $sender->sendMessage(Main::$prefix . "Vous venez de passé en gamemode créatif !");
            } elseif ($args[0] === "s" or $args[0] === "0" or $args[0] === "survival") {
                $sender->setGamemode(0);
                $sender->sendMessage(Main::$prefix . "Vous venez de passé en gamemode survie !");
            } elseif ($args[0] === "2" or $args[0] === "a" or $args[0] === "adventure") {
                $sender->setGamemode(2);
                $sender->sendMessage(Main::$prefix . "Vous venez de passé en gamemode aventure !");
            } elseif ($args[0] === "3" or $args[0] === "spectator") {
                $sender->setGamemode(3);
                $sender->sendMessage(Main::$prefix . "Vous venez de passé en gamemode spectateur !");
            } else $player->sendMessage(Main::$prefix . "Le gamemode indiqué n'est pas correct !");
        } else {
            if ($args[0] === "c" or $args[0] === "1" or $args[0] === "creative") {
                $player->setGamemode(1);
                $player->sendMessage(Main::$prefix . "Vous venez de passé en gamemode créatif !");
            } elseif ($args[0] === "s" or $args[0] === "0" or $args[0] === "survival") {
                $player->setGamemode(0);
                $player->sendMessage(Main::$prefix . "Vous venez de passé en gamemode survie !");
            } elseif ($args[0] === "2" or $args[0] === "a" or $args[0] === "adventure") {
                $player->setGamemode(2);
                $player->sendMessage(Main::$prefix . "Vous venez de passé en gamemode aventure !");
            } elseif ($args[0] === "3" or $args[0] === "spectator") {
                $player->setGamemode(3);
                $player->sendMessage(Main::$prefix . "Vous venez de passé en gamemode spectateur !");
            } else $player->sendMessage(Main::$prefix . "Le gamemode indiqué n'est pas correct !");
        }
        return true;
    }
}