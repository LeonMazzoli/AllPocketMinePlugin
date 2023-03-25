<?php

namespace Digueloulou12\InventorySynchronizer\Events;

use Digueloulou12\InventorySynchronizer\API\DatabaseAPI;
use Digueloulou12\InventorySynchronizer\API\InventoryAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class InventorySynchronizerEvents implements Listener
{
    public function onJoin(PlayerJoinEvent $event): void
    {
        if (DatabaseAPI::existInventory($event->getPlayer()->getName())) {
            InventoryAPI::setInventoryContents($event->getPlayer());
        } else InventoryAPI::saveInventory($event->getPlayer(), true);
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        InventoryAPI::saveInventory($event->getPlayer());
    }
}