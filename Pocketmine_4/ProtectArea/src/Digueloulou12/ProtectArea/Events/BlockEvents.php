<?php

namespace Digueloulou12\ProtectArea\Events;

use Digueloulou12\ProtectArea\ProtectArea;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class BlockEvents implements Listener
{
    public function onBreak(BlockBreakEvent $event): void
    {
        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "break", $event->getBlock()->getPosition())) $event->cancel();
    }

    public function onPlace(BlockPlaceEvent $event): void
    {
        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "place", $event->getBlock()->getPosition())) $event->cancel();
    }
}