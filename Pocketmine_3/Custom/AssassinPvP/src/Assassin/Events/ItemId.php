<?php

namespace Assassin\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;

class ItemId implements Listener{
    public function ItemHeld(PlayerItemHeldEvent $event)
    {
        $pl = $event->getPlayer();
        if($pl->isCreative()){
            $pl->sendTip("§l§eID§r§f: " . $event->getItem()->getId() . ":" . $event->getItem()->getDamage());
        }
    }
}