<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use THS\API\Game;

class Coal implements Listener{
    public function onUse(PlayerInteractEvent $event){
        if ($event->getItem()->getId() === ItemIds::COAL){
            Game::removeItem($event->getPlayer());
            $event->getPlayer()->getInventory()->removeItem(Item::get(Item::COAL, 0, 1));
            Game::addItem($event->getPlayer());
        }
    }
}