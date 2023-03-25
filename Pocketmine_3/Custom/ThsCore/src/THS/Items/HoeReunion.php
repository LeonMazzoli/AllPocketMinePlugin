<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;
use THS\Forms\ReunionForm;

class HoeReunion implements Listener{
    public function onUse(PlayerInteractEvent $event){
        if ($event->getItem()->getId() === ItemIds::DIAMOND_HOE){
            if ($event->getPlayer()->hasPermission("reunion")){
                ReunionForm::form($event->getPlayer());
            }
        }
    }
}