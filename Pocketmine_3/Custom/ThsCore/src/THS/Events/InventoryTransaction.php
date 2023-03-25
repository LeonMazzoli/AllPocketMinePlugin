<?php

namespace THS\Events;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

class InventoryTransaction implements Listener{
    public function onBouge(InventoryTransactionEvent $event){
        foreach ($event->getTransaction()->getActions() as $inventoryAction) {
            $sourceItem = $inventoryAction->getSourceItem();
            $targetItem = $inventoryAction->getTargetItem();
            if ((($sourceItem->getId() === ItemIds::ENDER_CHEST) or ($targetItem->getId() === ItemIds::ENDER_CHEST)) or
                (($sourceItem->getId() === ItemIds::DRAGON_BREATH) or ($targetItem->getId() === ItemIds::DRAGON_BREATH)) or
                (($sourceItem->getId() === ItemIds::DIAMOND_SWORD) or ($targetItem->getId() === ItemIds::DIAMOND_SWORD)) or
                (($sourceItem->getId() === ItemIds::COMPASS) or ($targetItem->getId() === ItemIds::COMPASS))) {
                $event->setCancelled(true);
            }
        }
    }
}