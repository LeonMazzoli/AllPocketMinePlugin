<?php

namespace Digueloulou12\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use Digueloulou12\TpaSystem;

class TpaDamageEvents implements Listener
{
    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            if ((in_array($player->getName(), TpaSystem::$players)) and (TpaSystem::$players[$player->getName()] < time())) {
                $event->cancel();
            }
        }
    }

    public function onDamageByEntity(EntityDamageByEntityEvent $event)
    {
        $player = $event->getDamager();
        if ($player instanceof Player) {
            if ((in_array($player->getName(), TpaSystem::$players)) and (TpaSystem::$players[$player->getName()] < time())) {
                $event->cancel();
            }
        }
    }
}