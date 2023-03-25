<?php

namespace Digueloulou12\ProtectArea\Events;

use Digueloulou12\ProtectArea\ProtectArea;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;

class EntityEvents implements Listener
{
    public function onDamage(EntityDamageEvent $event): void
    {
        if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
            if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getEntity(), "fall-damage", $event->getEntity()->getPosition())) $event->cancel();
            return;
        }

        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getEntity(), "invincible", $event->getEntity()->getPosition())) $event->cancel();
    }

    public function onDamageBis(EntityDamageByEntityEvent $event): void
    {
        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getDamager(), "pvp", $event->getEntity()->getPosition())) $event->cancel();
    }

    public function onExplode(EntityExplodeEvent $event): void
    {
        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getEntity(), "explosion", $event->getPosition())) $event->cancel();
    }

    public function onLaunch(ProjectileLaunchEvent $event): void
    {
        if ($event->getEntity() instanceof Arrow) {
            if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getEntity()->getOwningEntity(), "bow", $event->getEntity()->getOwningEntity()->getPosition())) $event->cancel();
        }
    }
}