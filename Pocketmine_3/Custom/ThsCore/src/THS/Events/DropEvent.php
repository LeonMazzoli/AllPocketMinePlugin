<?php

namespace THS\Events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;

class DropEvent implements Listener
{
    public function onDrop(PlayerDropItemEvent $event)
    {
        if ($event->getPlayer()->isOp()){
            $event->getPlayer()->getInventory()->removeItem($event->getItem());
            return;
        }

        $event->setCancelled();
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $event->setDrops([]);
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $event->setDrops([]);
    }
}