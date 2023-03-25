<?php

namespace THS\Events;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\Play\KbAPI;

class DamageEvent implements Listener{
    public function onDamage(EntityDamageEvent $event){
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;
        if ($event->getCause() === EntityDamageEvent::CAUSE_FALL){
            $event->setCancelled(true);
        }

        if ($event->getEntity()->getLevel()->getName() === "KB"){
            if ($event->getEntity()->getY() <= 55){
                $player->getInventory()->clearAll();
                KbAPI::startKB($player);
                return;
            }
        }

        if ($player->getLevel()->getName() === "Arc"){
            if ($event->getCause() === EntityDamageEvent::CAUSE_PROJECTILE) {
                $event->setBaseDamage(3);
            }

            if ($event->getEntity()->getY() <= 30){
                Server::getInstance()->getCommandMap()->dispatch($player, "hub");
            }
        }
    }
}