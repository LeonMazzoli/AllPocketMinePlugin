<?php

namespace Digueloulou12\Events;

use pocketmine\event\Listener;
use Digueloulou12\API\FactionAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;

class ClaimEvents implements Listener
{
    public function onInteract(PlayerInteractEvent $event)
    {
        if ($event->getBlock()->getId() === 0) {
            $chunk = $event->getPlayer()->getLevel()->getChunkAtPosition($event->getPlayer());
        } else $chunk = $event->getPlayer()->getLevel()->getChunkAtPosition($event->getBlock());

        if (FactionAPI::isChunkClaim($chunk)) {
            if (FactionAPI::getFactionClaim($chunk) !== FactionAPI::getFactionPlayer($event->getPlayer())) {
                $event->setCancelled(true);
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $chunk = $event->getPlayer()->getLevel()->getChunkAtPosition($event->getBlock());

        if (FactionAPI::isChunkClaim($chunk)) {
            if (FactionAPI::getFactionClaim($chunk) !== FactionAPI::getFactionPlayer($event->getPlayer())) {
                $event->setCancelled(true);
            }
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $chunk = $event->getPlayer()->getLevel()->getChunkAtPosition($event->getBlock());

        if (FactionAPI::isChunkClaim($chunk)) {
            if (FactionAPI::getFactionClaim($chunk) !== FactionAPI::getFactionPlayer($event->getPlayer())) {
                $event->setCancelled(true);
            }
        }
    }
}