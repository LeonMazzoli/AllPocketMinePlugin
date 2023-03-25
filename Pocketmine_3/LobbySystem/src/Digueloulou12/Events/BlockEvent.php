<?php

namespace Digueloulou12\Events;

use Digueloulou12\LobbyMain;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class BlockEvent implements Listener{
    public function onBreak(BlockBreakEvent $event){
        if (LobbyMain::getConfigValue("break") === false){
            if (LobbyMain::getConfigValue("op") === false){
                $event->setCancelled(true);
            }else{
                if (!$event->getPlayer()->isOp()){
                    $event->setCancelled(true);
                }
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event){
        if (LobbyMain::getConfigValue("place") === false){
            if (LobbyMain::getConfigValue("op") === false){
                $event->setCancelled(true);
            }else{
                if (!$event->getPlayer()->isOp()){
                    $event->setCancelled(true);
                }
            }
        }
    }
}