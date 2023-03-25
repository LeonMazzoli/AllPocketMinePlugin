<?php

namespace God;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class Event implements Listener
{
    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;
        if (isset(Command::$god[$player->getName()])) {
            $event->setCancelled(true);
        }
    }
}