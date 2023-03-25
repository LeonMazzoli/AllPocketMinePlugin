<?php

namespace THS\Events;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\item\ItemIds;

class ConsumeEvent implements Listener{
    public function onConsume(PlayerItemConsumeEvent $event){
        if ($event->getItem()->getId() === ItemIds::GOLDEN_APPLE){
            $event->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED),20*120, 0, false));
        }
    }
}