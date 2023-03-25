<?php

namespace THS\Commands\Boutique;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\PlayersAPI;
use THS\Main;

class SetPb extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("setpb", $main);
        $this->setPermission("setpb.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("setpb")) return true;
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un joueur !");
        if (!isset($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiqué le nombre de point boutique a définir !");
        if (!is_numeric($args[1])) return $player->sendMessage(Main::$prefix."La quantité doit être en chiffre !");

        if (Server::getInstance()->getPlayer($args[0]) !== null){
            $senderr = Server::getInstance()->getPlayer($args[0]);
            $sender = $senderr->getName();
        }else $sender = $args[0];

        PlayersAPI::setInfo($sender, "boutique", $args[1]);

        $player->sendMessage(Main::$prefix."Vous venez de définir§c $args[1] §7point(s) boutique à§c $sender §7!");
        return true;
    }
}