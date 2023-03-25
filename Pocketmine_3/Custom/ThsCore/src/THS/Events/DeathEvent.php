<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;

class DeathEvent implements Listener
{
    public function onDeath(PlayerDeathEvent $event)
    {
        if ($event->getPlayer()->getLevel()->getName() === "Lobby") $event->setDrops([]);
    }
}