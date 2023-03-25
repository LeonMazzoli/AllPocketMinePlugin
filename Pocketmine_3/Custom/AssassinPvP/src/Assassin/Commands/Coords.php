<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\Player;

class Coords extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("coords", $main);
        $this->setDescription("Active ou désactive l'affichage des coordonnées");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) return $player->sendMessage(Main::$prefix."La commande doit être utilisé en jeu !");
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix."Vous devez indiqué§a on §fou§a off §f!");
        if ($args[0] != "on" and $args[0] != "off") return $player->sendMessage(Main::$prefix."Vous devez indiquer §aon §fou§a off §f!");


        if ($args[0] === "on"){
            $co = new GameRulesChangedPacket();
            $co->gameRules = ["showcoordinates" => [1, true]];
            $player->dataPacket($co);
            $player->sendMessage(Main::$prefix."Vous venez d'activer vos coordonnées !");
        }else{
            $co = new GameRulesChangedPacket();
            $co->gameRules = ["showcoordinates" => [1, false]];
            $player->dataPacket($co);
            $player->sendMessage(Main::$prefix."Vous venez de désactiver vos coordonnées !");
        }
        return true;
    }
}