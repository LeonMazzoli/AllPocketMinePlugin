<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\Main;

class Sanction extends PluginCommand
{
    public function __construct(Main $main)
    {
        parent::__construct("sanction", $main);
        $this->setPermission("sanction.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $sanction = new Config(Main::getInstance()->getDataFolder() . "sanction.json", Config::JSON);
        if (!$player->hasPermission("sanction.use")) return $player->sendMessage(Main::$noperm);
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix . "Vous devez indiquer un joueur !");
        if ((Server::getInstance()->getPlayer($args[0]) === null) and !$sanction->exists(strtolower($args[0]))) return $player->sendMessage(Main::$prefix . "Le joueur indiquer n'existe pas !");


        if (Server::getInstance()->getPlayer($args[0]) === null) {
            $sender = strtolower($args[0]);
        } else {
            $senderr = Server::getInstance()->getPlayer($args[0]);
            $sender = strtolower($senderr->getName());
        }

        $kick = $sanction->getNested("$sender.kick");
        $mute = $sanction->getNested("$sender.mute");
        $ban = $sanction->getNested("$sender.ban");
        $banip = $sanction->getNested("$sender.banip");
        $player->sendMessage(Main::$prefix . "Le joueur§a $args[0] §fa été mute §a$mute §ffois, ban§a $ban §ffois, kick§a $kick §ffois et banip§a $banip §ffois !");
        return true;
    }
}