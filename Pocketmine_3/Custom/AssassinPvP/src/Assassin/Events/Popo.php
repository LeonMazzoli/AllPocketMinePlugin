<?php

namespace Assassin\Events;

use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class Popo implements Listener
{
    public function onInter(ProjectileLaunchEvent $event)
    {
        $player = $event->getEntity()->getOwningEntity();
        if ((!$player instanceof Player)) return;
        $popo = $player->getInventory()->getItemInHand();

        if ($player->getLevel()->getName() === "AreneJap") {
            if ($popo->getId() === 438 and $popo->getDamage() === 22) {
                $event->setCancelled(true);
                $player->setHealth($player->getHealth() + 4);
            }
        }
    }
}