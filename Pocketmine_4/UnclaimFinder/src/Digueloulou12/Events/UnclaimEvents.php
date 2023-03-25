<?php

namespace Digueloulou12\Events;

use Digueloulou12\Unclaim;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\Listener;

class UnclaimEvents implements Listener
{
    public function onUse(PlayerItemUseEvent $event)
    {
        if ($event->getItem()->getId() === Unclaim::getInstance()->getUnclaimFinderItem()->getId()) {
            $count = 0;
            $x = $event->getPlayer()->getPosition()->getFloorX() >> 4;
            $z = $event->getPlayer()->getPosition()->getFloorZ() >> 4;
            foreach ($event->getPlayer()->getWorld()->getChunk($x, $z)->getTiles() as $tile) {
                $count++;
            }
            $event->getPlayer()->sendPopup(str_replace("{tiles}", $count, Unclaim::getInstance()->getConfig()->get("popup")));
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        if ($event->getItem()->getId() === Unclaim::getInstance()->getUnclaimFinderItem()->getId()) {
            $count = 0;
            $x = $event->getPlayer()->getPosition()->getFloorX() >> 4;
            $z = $event->getPlayer()->getPosition()->getFloorZ() >> 4;
            foreach ($event->getPlayer()->getWorld()->getChunk($x, $z)->getTiles() as $tile) {
                $count++;
            }
            $event->getPlayer()->sendPopup(str_replace("{tiles}", $count, Unclaim::getInstance()->getConfig()->get("popup")));
        }
    }
}