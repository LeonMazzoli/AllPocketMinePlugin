<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;

class HealStick implements Listener
{
    private static $stick = [];

    public function onUse(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        if ($event->getItem()->getId() !== ItemIds::SHEARS) return;
        if (($event->getAction() !== 1) and ($event->getAction() !== 3)) return;

        if (empty(self::$stick[$player->getName()]) or self::$stick[$player->getName()] < time()) {
            self::$stick[$player->getName()] = time() + 10;
            $player->setHealth($player->getHealth() + 10);
            $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
        } else {
            $time = self::$stick[$player->getName()] - time();
            $player->sendTip("§a- §f$time §a-");
        }
    }
}