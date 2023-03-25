<?php

namespace THS\Items;

use pocketmine\entity\projectile\SplashPotion;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class Popo implements Listener
{
    public function onUse(ProjectileHitEvent $event)
    {
        $entity = $event->getEntity();

        if (!($entity instanceof SplashPotion)) return;

        $player = $entity->getOwningEntity();

        if (!($player instanceof Player)) return;

        $effects = $entity->getPotionEffects();
        foreach ($effects as $effect) {
            $player->addEffect($effect);
        }
        $entity->setPotionId(0);
    }
}