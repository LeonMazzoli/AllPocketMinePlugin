<?php

namespace Assassin\Events;

use Assassin\Commands\God;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class GodEvent implements Listener{
    public function godDamage(EntityDamageEvent $event){
        $player = $event->getEntity();
        if ($player instanceof Player){
            if (isset(God::$god[$player->getName()])){
                $event->setCancelled(true);
            }
        }
    }
}