<?php

namespace THS\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class EntityDamageEventByEntitity implements Listener
{
    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        $sender = $event->getDamager();

        if (!($player instanceof Player) or !($sender instanceof Player)) return;
        if ($player->getLevel()->getName() === "Arc") {
            if (($sender->getInventory()->getItemInHand()->getId() !== 261) or ($sender->getInventory()->getItemInHand()->getId() !== 332)) {
                $event->setCancelled(true);
            }
        }
    }
}