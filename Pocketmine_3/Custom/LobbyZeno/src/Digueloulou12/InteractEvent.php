<?php

namespace Digueloulou12;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;

class InteractEvent implements Listener{
    public function onUse(PlayerInteractEvent $event){
        if ($event->getItem()->getId() !== ItemIds::COMPASS) return;

        CompassForm::form($event->getPlayer());
    }
}