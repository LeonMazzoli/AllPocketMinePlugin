<?php

namespace THS\Events;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class GodEvent implements Listener{
    public static $god = [];
    public function onDamage(EntityDamageEvent $event){
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;
        if (isset(self::$god[$player->getName()])){
            $event->setCancelled(true);
        }
    }
}