<?php

namespace THS\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class KbGame implements Listener
{
    public function onDamage(EntityDamageByEntityEvent $event)
    {
        if (!($event->getEntity() instanceof Player) or !($event->getDamager() instanceof Player)) return;
        $hand = $event->getDamager()->getInventory()->getItemInHand();
        if ($hand->getId() !== 369) return;
        $kb = $event->getKnockBack();
        $event->setKnockBack($kb * floatval(7));
    }
}