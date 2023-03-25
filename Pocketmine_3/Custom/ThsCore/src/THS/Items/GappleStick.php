<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;

class GappleStick implements Listener{
    public function onUse(PlayerInteractEvent $event){
        $player = $event->getPlayer();

        if (($event->getAction() !== 1) and ($event->getAction() !== 3)) return;
        if ($event->getItem()->getId() !== ItemIds::SLIME_BALL) return;

        if ($player->getHealth() !== $player->getMaxHealth()){
            $player->setHealth($player->getHealth() + 4);
            $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
        }
    }
}