<?php

namespace Digueloulou12;

use pocketmine\entity\projectile\SplashPotion;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\Listener;
use pocketmine\item\PotionType;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PotionPlugin extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if (!file_exists($this->getDataFolder()."config.yml")) {
            new Config($this->getDataFolder()."config.yml", Config::YAML, [
                "radius" => 5
            ]);
        }
    }

    public function onHit(ProjectileHitBlockEvent $event)
    {
        $item = $event->getEntity();
        if ($item instanceof SplashPotion) {
            $pos = $item->getPosition();

            $radius = $this->getConfig()->get("radius") ?? 5;
            $bb = new AxisAlignedBB(
                $pos->x - $radius,
                $pos->y - $radius,
                $pos->z - $radius,
                $pos->x + $radius,
                $pos->y + $radius,
                $pos->z + $radius
            );
            foreach ($item->getWorld()->getNearbyEntities($bb) as $entity) {
                if ($entity instanceof Player) {
                    foreach ($item->getPotionEffects() as $effect) {
                        $entity->getEffects()->add($effect);
                    }
                }
            }

            $item->setPotionType(PotionType::WATER());
        }
    }
}