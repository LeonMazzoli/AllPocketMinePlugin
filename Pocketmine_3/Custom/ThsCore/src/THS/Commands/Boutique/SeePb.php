<?php

namespace THS\Commands\Boutique;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\PlayersAPI;
use THS\Main;

class SeePb extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("seepb", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!isset($args[0])){
            $player->sendMessage(Main::$prefix."Vous devez indiquer un joueur !");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) !== null){
            $senderr = Server::getInstance()->getPlayer($args[0]);
            $sender = $senderr->getName();
        }else $sender = $args[0];

        $money = PlayersAPI::getInfo($sender, "boutique");
        $player->sendMessage(Main::$prefix."Le joueur§a $sender §fà§a $money §fpoint(s) boutique !");
    }
}