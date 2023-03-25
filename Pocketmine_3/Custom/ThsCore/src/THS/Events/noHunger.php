<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

class noHunger implements Listener{
    public function onFood(PlayerExhaustEvent $event){
        $event->setCancelled(true);
    }
}