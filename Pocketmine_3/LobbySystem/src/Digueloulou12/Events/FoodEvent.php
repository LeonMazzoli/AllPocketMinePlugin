<?php

namespace Digueloulou12\Events;

use Digueloulou12\LobbyMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

class FoodEvent implements Listener{
    public function onFood(PlayerExhaustEvent $event){
        if (LobbyMain::getConfigValue("food") === false){
            $event->setCancelled(true);
        }
    }
}