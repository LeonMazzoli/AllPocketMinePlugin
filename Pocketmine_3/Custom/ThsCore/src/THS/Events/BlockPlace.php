<?php

namespace THS\Events;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class BlockPlace implements Listener{
    public function onPlace(BlockPlaceEvent $event){
        if (!$event->getPlayer()->isOp()){
            if ($event->getBlock()->getY() > 85){
                $event->setCancelled(true);
            }
        }
    }
}