<?php

namespace THS\Items;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;

class Feather implements Listener{
    public function onUse(PlayerInteractEvent $event){
        $player = $event->getPlayer();

        if ($event->getItem()->getId() !== ItemIds::FEATHER) return;

        $player->addEffect(new EffectInstance(Effect::getEffect(Effect::LEVITATION), 20 * 5, 2, false));

        $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
    }
}