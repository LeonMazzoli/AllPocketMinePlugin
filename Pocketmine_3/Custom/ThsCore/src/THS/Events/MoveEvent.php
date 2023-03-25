<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

class MoveEvent implements Listener{
    public function onMove(PlayerMoveEvent $event){
        $world = ["Laser-0", "Laser-1", "Laser-melee"];
        if (in_array($event->getPlayer()->getLevel()->getName(), $world)){
            $event->getPlayer()->setNameTag("");
            $event->getPlayer()->setNameTagVisible(false);
        }else $event->getPlayer();
    }
}