<?php

namespace THS\Commands\Boutique;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\PlayersAPI;
use THS\Main;

class RemovePb extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("removepb", $main);
        $this->setPermission("removepb.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("removepb")) return true;
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un joueur !");
        if (!isset($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiqué le nombre de point boutique a retirer !");
        if (!is_numeric($args[1])) return $player->sendMessage(Main::$prefix."La quantité doit être en chiffre !");

        if (Server::getInstance()->getPlayer($args[0]) !== null){
            $senderr = Server::getInstance()->getPlayer($args[0]);
            $sender = $senderr->getName();
        }else $sender = $args[0];

        if (PlayersAPI::getInfo($sender, "boutique") <= $args[1]) return $player->sendMessage(Main::$prefix."Le joueur na pas assez de point(s) boutique pour lui retiré !");

        PlayersAPI::setInfo($sender, "boutique", PlayersAPI::getInfo($sender, "boutique") - $args[1]);

        $player->sendMessage(Main::$prefix."Vous venez de retirer§a $args[1] §fpoint(s) boutique à§a $sender §f!");
        return true;
    }
}