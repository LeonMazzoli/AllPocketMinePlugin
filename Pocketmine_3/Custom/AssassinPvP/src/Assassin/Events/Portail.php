<?php

namespace Assassin\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;

class Portail implements Listener{
    public function onMove(PlayerMoveEvent $event){
        $player = $event->getPlayer();

        if ($player->getLevel()->getBlock($player)->getId() === 90){
            if ($player->getFloorX() === 940){
                Server::getInstance()->dispatchCommand($player, "gogapple");
            }elseif ($player->getFloorX() === 1041){
                Server::getInstance()->dispatchCommand($player, "gopopo");
            }elseif ($player->getFloorZ() === 1014){
                Server::getInstance()->getCommandMap()->dispatch($player, "goarc");
            }elseif ($player->getFloorZ() === 914){
                Server::getInstance()->dispatchCommand($player, "gosnow");
            }
        }
    }
}