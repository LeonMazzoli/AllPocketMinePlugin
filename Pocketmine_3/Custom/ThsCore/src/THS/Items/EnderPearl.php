<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;

class EnderPearl implements Listener
{
    private static $pearl = [];

    /**
     * @param PlayerInteractEvent $event
     * @priority LOWEST
     */
    public function onUse(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        if ($event->getItem()->getId() !== ItemIds::ENDER_PEARL) return;
        if (($event->getAction() !== 1) and ($event->getAction() !== 3)) return;

        if (empty(self::$pearl[$player->getName()]) or self::$pearl[$player->getName()] < time()) {
            self::$pearl[$player->getName()] = time() + 10;
        } else {
            $event->setCancelled(true);
            $time = self::$pearl[$player->getName()] - time();
            $player->sendTip("§a- §f$time §a-");
        }
    }
}