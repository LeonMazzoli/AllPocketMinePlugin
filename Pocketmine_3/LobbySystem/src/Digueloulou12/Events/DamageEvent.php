<?php

namespace Digueloulou12\Events;

use Digueloulou12\LobbyMain;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class DamageEvent implements Listener
{
    public function onDamage(EntityDamageEvent $event)
    {
        if (LobbyMain::getConfigValue("damage") === false){
            $event->setCancelled(true);
        }
    }
}