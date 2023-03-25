<?php

namespace Digueloulou12\LobbyCore\Events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class BlockEvents implements Listener
{
    public function onPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        if (!$player->getServer()->isOp($player->getName())) $event->cancel();
    }

    public function onBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        if (!$player->getServer()->isOp($player->getName())) $event->cancel();
    }
}