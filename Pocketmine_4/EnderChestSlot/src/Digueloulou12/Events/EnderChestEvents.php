<?php

namespace Digueloulou12\Events;

use Digueloulou12\EnderChestSlot;
use pocketmine\block\inventory\EnderChestInventory;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class EnderChestEvents implements Listener
{
    public function onInventory(InventoryTransactionEvent $event)
    {
        foreach ($event->getTransaction()->getActions() as $action) {
            $items = [$action->getTargetItem(), $action->getSourceItem()];
            /** @var Item $item */
            foreach ($items as $item) {
                if ($item->getNamedTag()->getTag("EnderChestSlot") !== null) {
                    $event->cancel();
                }
            }
        }
    }

    public function onOpen(InventoryOpenEvent $event)
    {
        if (($event->getInventory()->getItem(0)->getNamedTag()->getTag("EnderChestCommand") !== null) or ($event->getInventory() instanceof EnderChestInventory)) {
            $player = $event->getPlayer();
            foreach ($event->getInventory()->getContents() as $slot => $item) {
                if ($item->getNamedTag()->getTag("EnderChestSlot") !== null) {
                    $event->getInventory()->setItem($slot, ItemFactory::air());
                }
            }

            $config = EnderChestSlot::$config;
            $slot = $config->get("slot_default");
            foreach ($config->get("slots") as $perm => $int) {
                if ($player->hasPermission($perm)) $slot = $int;
            }

            $ic = $config->get("item");
            $item = ItemFactory::getInstance()->get($ic[0], $ic[1], 1)->setCustomName($ic[2]);
            $item->getNamedTag()->setString("EnderChestSlot", "EnderChestSlot");
            for ($i = $slot; $i !== 27; $i++) {
                if ($event->getInventory()->getItem($i)->getNamedTag()->getTag("EnderChestSlot") !== null) {
                    $player->getWorld()->dropItem($player->getPosition(), $event->getInventory()->getItem($i));
                }

                $event->getInventory()->setItem($i, $item);
            }
        }
    }
}