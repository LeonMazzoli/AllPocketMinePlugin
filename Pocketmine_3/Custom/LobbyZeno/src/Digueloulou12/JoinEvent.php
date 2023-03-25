<?php

namespace Digueloulou12;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;

class JoinEvent implements Listener{
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();

        $player->getInventory()->setItem(4, Item::get(Item::COMPASS, 0, 1)->setCustomName("Servers"));
    }
}