<?php

namespace Digueloulou12;

use pocketmine\entity\projectile\SplashPotion;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\Listener;
use pocketmine\item\PotionType;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class PotionPlugin extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onHit(ProjectileHitBlockEvent $event)
    {
        $item = $event->getEntity();
        $player = $event->getEntity()->getOwningEntity();
        if ($player instanceof Player) {
            if ($item instanceof SplashPotion) {
                foreach ($item->getPotionEffects() as $effect) {
                    $player->getEffects()->add($effect);
                }
                $item->setPotionType(PotionType::WATER());
            }
        }
    }
}