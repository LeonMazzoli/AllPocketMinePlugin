<?php

namespace THS\Items;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;

class SpeedStick implements Listener
{
    private static $stick = [];

    public function onUse(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        if ($event->getItem()->getId() !== ItemIds::MINECART) return;
        if (($event->getAction() !== 1) and ($event->getAction() !== 3)) return;

        if (empty(self::$stick[$player->getName()]) or self::$stick[$player->getName()] < time()) {
            self::$stick[$player->getName()] = time() + 20;
            $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 10 * 20, 2, false));
            $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
        } else {
            $time = self::$stick[$player->getName()] - time();
            $player->sendTip("§a- §f$time §a-");
        }
    }
}