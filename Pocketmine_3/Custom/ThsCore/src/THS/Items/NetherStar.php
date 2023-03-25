<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;
use THS\Forms\ShopRushForms;

class NetherStar implements Listener{
    public function onUse(PlayerInteractEvent $event){
        if ($event->getItem()->getId() !== ItemIds::NETHER_STAR) return;
        ShopRushForms::form($event->getPlayer());
    }
}